<?php
require_once __DIR__.'/db.php';
require_once __DIR__.'/helpers.php';
start_session_once();

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';
$data = request_data();

try {
    switch ($action) {
        case 'session':
            json_response(true, '', ['logged_in'=>!empty($_SESSION['user_id']), 'user'=>empty($_SESSION['user_id'])?null:[
                'id'=>(int)$_SESSION['user_id'],'full_name'=>$_SESSION['user_name'],'email'=>$_SESSION['user_email'],'role'=>$_SESSION['user_role']
            ]]);

        case 'login':
            if ($method !== 'POST') json_response(false,'Method not allowed.',null,405);
            $email=clean_string($data['email']??''); $password=(string)($data['password']??'');
            if (!filter_var($email,FILTER_VALIDATE_EMAIL) || $password==='') json_response(false,'Enter a valid email and password.',null,422);
            $stmt=$pdo->prepare('SELECT * FROM users WHERE email=? LIMIT 1'); $stmt->execute([$email]); $user=$stmt->fetch();
            if (!$user || !password_verify($password,$user['password'])) json_response(false,'Invalid email or password.',null,401);
            session_regenerate_id(true);
            $_SESSION['user_id']=(int)$user['id']; $_SESSION['user_name']=$user['full_name']; $_SESSION['user_email']=$user['email']; $_SESSION['user_role']=$user['role'];
            json_response(true,'Welcome back, '.$user['full_name'].'!',['id'=>(int)$user['id'],'full_name'=>$user['full_name'],'email'=>$user['email'],'role'=>$user['role']]);

        case 'register':
            if ($method !== 'POST') json_response(false,'Method not allowed.',null,405);
            $name=clean_string($data['full_name']??''); $email=clean_string($data['email']??''); $password=(string)($data['password']??''); $confirm=(string)($data['confirm_password']??''); $role=($data['role']??'student')==='admin'?'admin':'student';
            if ($name==='' || !filter_var($email,FILTER_VALIDATE_EMAIL) || strlen($password)<6 || $password!==$confirm) json_response(false,'Check your name, email, password (minimum 6 characters), and confirmation.',422);
            $check=$pdo->prepare('SELECT id FROM users WHERE email=?'); $check->execute([$email]); if($check->fetch()) json_response(false,'An account with this email already exists.',409);
            $stmt=$pdo->prepare('INSERT INTO users(full_name,email,password,role) VALUES(?,?,?,?)'); $stmt->execute([$name,$email,password_hash($password,PASSWORD_DEFAULT),$role]);
            json_response(true,'Registration successful. You can now log in.');

        case 'logout':
            if ($method !== 'POST') json_response(false,'Method not allowed.',null,405);
            $_SESSION=[]; if(ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy();
            json_response(true,'You have been logged out.');

        case 'categories':
            require_login_api();
            if ($method==='GET') { $rows=$pdo->query('SELECT c.id,c.name,c.description,c.created_at,COUNT(e.id) event_count FROM categories c LEFT JOIN events e ON e.category_id=c.id GROUP BY c.id ORDER BY c.name')->fetchAll(); json_response(true,'',$rows); }
            require_role_api('admin');
            if($method!=='POST') json_response(false,'Method not allowed.',null,405);
            $op=$data['op']??'create'; $id=(int)($data['id']??0); $name=clean_string($data['name']??''); $desc=clean_string($data['description']??'');
            if($op==='delete'){ if($id<=0) json_response(false,'Invalid category.',null,422); $stmt=$pdo->prepare('DELETE FROM categories WHERE id=?'); $stmt->execute([$id]); json_response(true,'Category deleted.'); }
            if($name==='') json_response(false,'Category name is required.',422);
            if($op==='update'){ $stmt=$pdo->prepare('UPDATE categories SET name=?,description=? WHERE id=?'); $stmt->execute([$name,$desc,$id]); json_response(true,'Category updated.'); }
            $stmt=$pdo->prepare('INSERT INTO categories(name,description) VALUES(?,?)'); $stmt->execute([$name,$desc]); json_response(true,'Category created.');

        case 'events':
            require_login_api();
            if($method==='GET'){
                $where=''; $params=[]; if(isset($_GET['id'])){$where='WHERE e.id=?';$params[]=(int)$_GET['id'];}
                $stmt=$pdo->prepare("SELECT e.*,c.name category_name,u.full_name organizer_name,u.email organizer_email FROM events e JOIN categories c ON c.id=e.category_id JOIN users u ON u.id=e.created_by $where ORDER BY e.event_date ASC,e.event_time ASC"); $stmt->execute($params); $events=$stmt->fetchAll(); $sid=($_SESSION['user_role']==='student')?(int)$_SESSION['user_id']:null;
                foreach($events as &$e){$s=$pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id=?');$s->execute([$e['id']]);$e['registered_count']=(int)$s->fetchColumn();$e['seats_left']=max(0,$e['capacity']-$e['registered_count']);$e['is_full']=$e['seats_left']<=0;$e['is_registered']=false;if($sid){$r=$pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id=? AND student_id=?');$r->execute([$e['id'],$sid]);$e['is_registered']=(bool)$r->fetchColumn();}} unset($e);
                json_response(true,'',$events);
            }
            require_role_api('admin');
            if($method!=='POST') json_response(false,'Method not allowed.',null,405);
            $op=$data['op']??'create'; $id=(int)($data['id']??0);
            if($op==='delete'){ $stmt=$pdo->prepare('DELETE FROM events WHERE id=?');$stmt->execute([$id]);json_response(true,'Event deleted.'); }
            $title=clean_string($data['title']??'');$description=clean_string($data['description']??'');$category=(int)($data['category_id']??0);$date=clean_string($data['event_date']??'');$time=clean_string($data['event_time']??'');$venue=clean_string($data['venue']??'');$capacity=(int)($data['capacity']??0);$status=clean_string($data['status']??'Upcoming');
            if($title===''||$description===''||$category<=0||$date===''||$time===''||$venue===''||$capacity<=0||!in_array($status,['Upcoming','Ongoing','Completed','Cancelled'],true)) json_response(false,'Please complete all event fields correctly.',422);
            if($op==='update'){ $stmt=$pdo->prepare('UPDATE events SET title=?,description=?,category_id=?,event_date=?,event_time=?,venue=?,capacity=?,status=? WHERE id=?');$stmt->execute([$title,$description,$category,$date,$time,$venue,$capacity,$status,$id]);json_response(true,'Event updated.'); }
            $stmt=$pdo->prepare('INSERT INTO events(title,description,category_id,event_date,event_time,venue,capacity,status,created_by) VALUES(?,?,?,?,?,?,?,?,?)');$stmt->execute([$title,$description,$category,$date,$time,$venue,$capacity,$status,$_SESSION['user_id']]);json_response(true,'Event created.');

        case 'event':
            require_login_api(); $id=(int)($_GET['id']??0); if($id<=0) json_response(false,'Invalid event.',null,422); $event=event_with_stats($pdo,$id,$_SESSION['user_role']==='student'?(int)$_SESSION['user_id']:null); if(!$event) json_response(false,'Event not found.',null,404); $a=$pdo->prepare('SELECT id,title,content,event_id,created_at FROM announcements WHERE event_id=? ORDER BY created_at DESC');$a->execute([$id]);$event['announcements']=$a->fetchAll();json_response(true,'',$event);

        case 'registration':
            require_role_api('student'); if($method!=='POST') json_response(false,'Method not allowed.',null,405); $id=(int)($data['event_id']??0);$op=$data['op']??'register'; $event=event_with_stats($pdo,$id,(int)$_SESSION['user_id']); if(!$event) json_response(false,'Event not found.',null,404);
            if($op==='cancel'){ $stmt=$pdo->prepare('DELETE FROM registrations WHERE event_id=? AND student_id=?');$stmt->execute([$id,$_SESSION['user_id']]);json_response(true,'Your registration was cancelled.');}
            if($event['status']!=='Upcoming' && $event['status']!=='Ongoing') json_response(false,'Registrations are closed for this event.',409); if($event['is_registered']) json_response(false,'You are already registered for this event.',409); if($event['is_full']) json_response(false,'This event has reached maximum capacity.',409);
            $pdo->beginTransaction(); try { $lock=$pdo->prepare('SELECT capacity FROM events WHERE id=? FOR UPDATE');$lock->execute([$id]);$cap=(int)$lock->fetchColumn();$c=$pdo->prepare('SELECT COUNT(*) FROM registrations WHERE event_id=?');$c->execute([$id]);$count=(int)$c->fetchColumn();if($count>=$cap) throw new RuntimeException('This event has reached maximum capacity.');$ins=$pdo->prepare('INSERT INTO registrations(event_id,student_id) VALUES(?,?)');$ins->execute([$id,$_SESSION['user_id']]);$pdo->commit(); } catch(Throwable $t){$pdo->rollBack();json_response(false,$t->getMessage(),null,409);} json_response(true,'Registration successful. The event is now in your schedule.');

        case 'schedule':
            require_role_api('student'); $stmt=$pdo->prepare("SELECT e.*,c.name category_name,r.registered_at FROM registrations r JOIN events e ON e.id=r.event_id JOIN categories c ON c.id=e.category_id WHERE r.student_id=? ORDER BY e.event_date ASC,e.event_time ASC");$stmt->execute([$_SESSION['user_id']]);json_response(true,'',$stmt->fetchAll());

        case 'announcements':
            require_login_api();
            if($method==='GET'){ $stmt=$pdo->query("SELECT a.*,e.title event_title FROM announcements a LEFT JOIN events e ON e.id=a.event_id ORDER BY a.created_at DESC");json_response(true,'',$stmt->fetchAll());}
            require_role_api('admin'); if($method!=='POST') json_response(false,'Method not allowed.',null,405);$op=$data['op']??'create';$id=(int)($data['id']??0);if($op==='delete'){ $s=$pdo->prepare('DELETE FROM announcements WHERE id=?');$s->execute([$id]);json_response(true,'Announcement deleted.');}$title=clean_string($data['title']??'');$content=clean_string($data['content']??'');$eventId=(int)($data['event_id']??0);$eventId=$eventId>0?$eventId:null;if($title===''||$content==='')json_response(false,'Title and content are required.',422);if($op==='update'){$s=$pdo->prepare('UPDATE announcements SET title=?,content=?,event_id=? WHERE id=?');$s->execute([$title,$content,$eventId,$id]);json_response(true,'Announcement updated.');}$s=$pdo->prepare('INSERT INTO announcements(title,content,event_id,created_by) VALUES(?,?,?,?)');$s->execute([$title,$content,$eventId,$_SESSION['user_id']]);json_response(true,'Announcement posted.');

        case 'participants':
            require_role_api('admin'); $eventId=(int)($_GET['event_id']??0); if($eventId<=0)json_response(false,'Invalid event.',null,422);$s=$pdo->prepare("SELECT u.id,u.full_name,u.email,r.registered_at,e.title event_title FROM registrations r JOIN users u ON u.id=r.student_id JOIN events e ON e.id=r.event_id WHERE r.event_id=? ORDER BY r.registered_at ASC");$s->execute([$eventId]);json_response(true,'',$s->fetchAll());

        case 'admin_dashboard':
            require_role_api('admin');$counts=[];foreach(['events','categories','registrations','announcements'] as $t){$counts[$t]=(int)$pdo->query("SELECT COUNT(*) FROM $t")->fetchColumn();}$s=$pdo->query("SELECT e.id,e.title,e.event_date,e.event_time,e.status,c.name category_name,COUNT(r.id) registered_count,e.capacity FROM events e JOIN categories c ON c.id=e.category_id LEFT JOIN registrations r ON r.event_id=e.id GROUP BY e.id ORDER BY e.created_at DESC LIMIT 5");json_response(true,'',['counts'=>$counts,'recent_events'=>$s->fetchAll()]);

        default: json_response(false,'Unknown API action.',null,404);
    }
} catch (PDOException $e) {
    if($e->getCode()==='23000') json_response(false,'This record already exists or is referenced by another record.',null,409);
    json_response(false,'A database error occurred.',null,500);
}
