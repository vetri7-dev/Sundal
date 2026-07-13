<?php

namespace Database\Seeders;

use App\Models\EmailTemplate;
use App\Models\EmailTemplateLang;
use App\Models\UserEmailTemplate;
use Illuminate\Database\Seeder;

class EmailTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $supportedLanguages = ['en', 'es', 'ar', 'da', 'de', 'fr', 'he', 'it', 'ja', 'nl', 'pl', 'pt', 'pt-BR', 'ru', 'tr', 'zh'];

        $templates = [
            [
                'name' => 'Workspace Invitation',
                'from' => 'Support Team',
                'translations' => [
                    'en' => [
                        'subject' => 'You have been invited to join {workspace_name}',
                        'content' => '<h2>You have been invited to a workspace!</h2><p>Hello <strong>{user_name}</strong>,</p><p>You have been invited by <strong>{invited_by_name}</strong> to join the workspace "<strong>{workspace_name}</strong>".</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Invited by:</strong> {invited_by_name}</p><p><strong>Role:</strong> {role}</p><p>Click the button below to accept the invitation:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Accept Invitation</a></p><p>After accepting, you can start collaborating with your team members in this workspace.</p><p>Best regards,<br><strong>The {app_name} Team</strong></p>'
                    ],
                    'es' => [
                        'subject' => 'Has sido invitado a unirte a {workspace_name}',
                        'content' => '<h2>¡Has sido invitado a un espacio de trabajo!</h2><p>Hola <strong>{user_name}</strong>,</p><p>Has sido invitado por <strong>{invited_by_name}</strong> a unirte al espacio de trabajo "<strong>{workspace_name}</strong>".</p><p><strong>Espacio de trabajo:</strong> {workspace_name}</p><p><strong>Invitado por:</strong> {invited_by_name}</p><p><strong>Rol:</strong> {role}</p><p>Haz clic en el botón de abajo para aceptar la invitación:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Aceptar Invitación</a></p><p>Después de aceptar, puedes comenzar a colaborar con los miembros de tu equipo en este espacio de trabajo.</p><p>Saludos cordiales,<br><strong>El equipo de {app_name}</strong></p>'
                    ],
                    'ar' => [
                        'subject' => 'تمت دعوتك للانضمام إلى {workspace_name}',
                        'content' => '<h2>تمت دعوتك إلى مساحة عمل!</h2><p>مرحباً <strong>{user_name}</strong>،</p><p>تمت دعوتك من قبل <strong>{invited_by_name}</strong> للانضمام إلى مساحة العمل "<strong>{workspace_name}</strong>".</p><p><strong>مساحة العمل:</strong> {workspace_name}</p><p><strong>دعوة من:</strong> {invited_by_name}</p><p><strong>الدور:</strong> {role}</p><p>انقر على الزر أدناه لقبول الدعوة:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">قبول الدعوة</a></p><p>بعد القبول، يمكنك البدء في التعاون مع أعضاء فريقك في مساحة العمل هذه.</p><p>أطيب التحيات،<br><strong>فريق {app_name}</strong></p>'
                    ],
                    'da' => [
                        'subject' => 'Du er blevet inviteret til at deltage i {workspace_name}',
                        'content' => '<h2>Du er blevet inviteret til et arbejdsområde!</h2><p>Hej <strong>{user_name}</strong>,</p><p>Du er blevet inviteret af <strong>{invited_by_name}</strong> til at deltage i arbejdsområdet "<strong>{workspace_name}</strong>".</p><p><strong>Arbejdsområde:</strong> {workspace_name}</p><p><strong>Inviteret af:</strong> {invited_by_name}</p><p><strong>Rolle:</strong> {role}</p><p>Klik på knappen nedenfor for at acceptere invitationen:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Accepter invitation</a></p><p>Efter at have accepteret kan du begynde at samarbejde med dine teammedlemmer i dette arbejdsområde.</p><p>Med venlig hilsen,<br><strong>{app_name} Teamet</strong></p>'
                    ],
                    'de' => [
                        'subject' => 'Sie wurden eingeladen, {workspace_name} beizutreten',
                        'content' => '<h2>Sie wurden zu einem Arbeitsbereich eingeladen!</h2><p>Hallo <strong>{user_name}</strong>,</p><p>Sie wurden von <strong>{invited_by_name}</strong> eingeladen, dem Arbeitsbereich "<strong>{workspace_name}</strong>" beizutreten.</p><p><strong>Arbeitsbereich:</strong> {workspace_name}</p><p><strong>Eingeladen von:</strong> {invited_by_name}</p><p><strong>Rolle:</strong> {role}</p><p>Klicken Sie auf die Schaltfläche unten, um die Einladung anzunehmen:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Einladung annehmen</a></p><p>Nach der Annahme können Sie mit der Zusammenarbeit mit Ihren Teammitgliedern in diesem Arbeitsbereich beginnen.</p><p>Mit freundlichen Grüßen,<br><strong>Das {app_name} Team</strong></p>'
                    ],
                    'fr' => [
                        'subject' => 'Vous avez été invité à rejoindre {workspace_name}',
                        'content' => '<h2>Vous avez été invité à un espace de travail!</h2><p>Bonjour <strong>{user_name}</strong>,</p><p>Vous avez été invité par <strong>{invited_by_name}</strong> à rejoindre l\'espace de travail "<strong>{workspace_name}</strong>".</p><p><strong>Espace de travail:</strong> {workspace_name}</p><p><strong>Invité par:</strong> {invited_by_name}</p><p><strong>Rôle:</strong> {role}</p><p>Cliquez sur le bouton ci-dessous pour accepter l\'invitation:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Accepter l\'invitation</a></p><p>Après avoir accepté, vous pouvez commencer à collaborer avec les membres de votre équipe dans cet espace de travail.</p><p>Cordialement,<br><strong>L\'équipe {app_name}</strong></p>'
                    ],
                    'he' => [
                        'subject' => 'הוזמנת להצטרף ל-{workspace_name}',
                        'content' => '<h2>הוזמנת לסביבת עבודה!</h2><p>שלום <strong>{user_name}</strong>,</p><p>הוזמנת על ידי <strong>{invited_by_name}</strong> להצטרף לסביבת העבודה "<strong>{workspace_name}</strong>".</p><p><strong>סביבת עבודה:</strong> {workspace_name}</p><p><strong>הוזמן על ידי:</strong> {invited_by_name}</p><p><strong>תפקיד:</strong> {role}</p><p>לחץ על הכפתור למטה כדי לקבל את ההזמנה:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">קבל הזמנה</a></p><p>לאחר הקבלה, תוכל להתחיל לשתף פעולה עם חברי הצוות שלך בסביבת העבודה הזו.</p><p>בברכה,<br><strong>צוות {app_name}</strong></p>'
                    ],
                    'it' => [
                        'subject' => 'Sei stato invitato a unirti a {workspace_name}',
                        'content' => '<h2>Sei stato invitato a un workspace!</h2><p>Ciao <strong>{user_name}</strong>,</p><p>Sei stato invitato da <strong>{invited_by_name}</strong> a unirti al workspace "<strong>{workspace_name}</strong>".</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Invitato da:</strong> {invited_by_name}</p><p><strong>Ruolo:</strong> {role}</p><p>Clicca sul pulsante qui sotto per accettare l\'invito:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Accetta invito</a></p><p>Dopo aver accettato, puoi iniziare a collaborare con i membri del tuo team in questo workspace.</p><p>Cordiali saluti,<br><strong>Il team di {app_name}</strong></p>'
                    ],
                    'ja' => [
                        'subject' => '{workspace_name}への参加招待',
                        'content' => '<h2>ワークスペースに招待されました！</h2><p>こんにちは <strong>{user_name}</strong> さん、</p><p><strong>{invited_by_name}</strong> さんからワークスペース "<strong>{workspace_name}</strong>" への参加招待を受けました。</p><p><strong>ワークスペース:</strong> {workspace_name}</p><p><strong>招待者:</strong> {invited_by_name}</p><p><strong>役割:</strong> {role}</p><p>下のボタンをクリックして招待を受け入れてください:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">招待を受け入れる</a></p><p>受け入れ後、このワークスペースでチームメンバーとの協力を開始できます。</p><p>よろしくお願いいたします、<br><strong>{app_name} チーム</strong></p>'
                    ],
                    'nl' => [
                        'subject' => 'Je bent uitgenodigd om lid te worden van {workspace_name}',
                        'content' => '<h2>Je bent uitgenodigd voor een werkruimte!</h2><p>Hallo <strong>{user_name}</strong>,</p><p>Je bent uitgenodigd door <strong>{invited_by_name}</strong> om lid te worden van de werkruimte "<strong>{workspace_name}</strong>".</p><p><strong>Werkruimte:</strong> {workspace_name}</p><p><strong>Uitgenodigd door:</strong> {invited_by_name}</p><p><strong>Rol:</strong> {role}</p><p>Klik op de knop hieronder om de uitnodiging te accepteren:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Uitnodiging accepteren</a></p><p>Na acceptatie kun je beginnen met samenwerken met je teamleden in deze werkruimte.</p><p>Met vriendelijke groet,<br><strong>Het {app_name} Team</strong></p>'
                    ],
                    'pl' => [
                        'subject' => 'Zostałeś zaproszony do dołączenia do {workspace_name}',
                        'content' => '<h2>Zostałeś zaproszony do przestrzeni roboczej!</h2><p>Cześć <strong>{user_name}</strong>,</p><p>Zostałeś zaproszony przez <strong>{invited_by_name}</strong> do dołączenia do przestrzeni roboczej "<strong>{workspace_name}</strong>".</p><p><strong>Przestrzeń robocza:</strong> {workspace_name}</p><p><strong>Zaproszony przez:</strong> {invited_by_name}</p><p><strong>Rola:</strong> {role}</p><p>Kliknij przycisk poniżej, aby zaakceptować zaproszenie:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Zaakceptuj zaproszenie</a></p><p>Po zaakceptowaniu możesz rozpocząć współpracę z członkami zespołu w tej przestrzeni roboczej.</p><p>Z poważaniem,<br><strong>Zespół {app_name}</strong></p>'
                    ],
                    'pt' => [
                        'subject' => 'Você foi convidado para se juntar a {workspace_name}',
                        'content' => '<h2>Você foi convidado para um espaço de trabalho!</h2><p>Olá <strong>{user_name}</strong>,</p><p>Você foi convidado por <strong>{invited_by_name}</strong> para se juntar ao espaço de trabalho "<strong>{workspace_name}</strong>".</p><p><strong>Espaço de trabalho:</strong> {workspace_name}</p><p><strong>Convidado por:</strong> {invited_by_name}</p><p><strong>Função:</strong> {role}</p><p>Clique no botão abaixo para aceitar o convite:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Aceitar convite</a></p><p>Após aceitar, você pode começar a colaborar com os membros da sua equipe neste espaço de trabalho.</p><p>Atenciosamente,<br><strong>A equipe {app_name}</strong></p>'
                    ],
                    'pt-BR' => [
                        'subject' => 'Você foi convidado para se juntar a {workspace_name}',
                        'content' => '<h2>Você foi convidado para um espaço de trabalho!</h2><p>Olá <strong>{user_name}</strong>,</p><p>Você foi convidado por <strong>{invited_by_name}</strong> para se juntar ao espaço de trabalho "<strong>{workspace_name}</strong>".</p><p><strong>Espaço de trabalho:</strong> {workspace_name}</p><p><strong>Convidado por:</strong> {invited_by_name}</p><p><strong>Função:</strong> {role}</p><p>Clique no botão abaixo para aceitar o convite:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Aceitar convite</a></p><p>Após aceitar, você pode começar a colaborar com os membros da sua equipe neste espaço de trabalho.</p><p>Atenciosamente,<br><strong>A equipe {app_name}</strong></p>'
                    ],
                    'ru' => [
                        'subject' => 'Вас пригласили присоединиться к {workspace_name}',
                        'content' => '<h2>Вас пригласили в рабочее пространство!</h2><p>Привет <strong>{user_name}</strong>,</p><p>Вас пригласил <strong>{invited_by_name}</strong> присоединиться к рабочему пространству "<strong>{workspace_name}</strong>".</p><p><strong>Рабочее пространство:</strong> {workspace_name}</p><p><strong>Пригласил:</strong> {invited_by_name}</p><p><strong>Роль:</strong> {role}</p><p>Нажмите кнопку ниже, чтобы принять приглашение:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Принять приглашение</a></p><p>После принятия вы сможете начать сотрудничество с членами вашей команды в этом рабочем пространстве.</p><p>С наилучшими пожеланиями,<br><strong>Команда {app_name}</strong></p>'
                    ],
                    'tr' => [
                        'subject' => '{workspace_name} çalışma alanına katılmaya davet edildiniz',
                        'content' => '<h2>Bir çalışma alanına davet edildiniz!</h2><p>Merhaba <strong>{user_name}</strong>,</p><p><strong>{invited_by_name}</strong> tarafından "<strong>{workspace_name}</strong>" çalışma alanına katılmaya davet edildiniz.</p><p><strong>Çalışma alanı:</strong> {workspace_name}</p><p><strong>Davet eden:</strong> {invited_by_name}</p><p><strong>Rol:</strong> {role}</p><p>Daveti kabul etmek için aşağıdaki düğmeye tıklayın:</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">Daveti Kabul Et</a></p><p>Kabul ettikten sonra, bu çalışma alanında ekip üyelerinizle işbirliği yapmaya başlayabilirsiniz.</p><p>Saygılarımızla,<br><strong>{app_name} Ekibi</strong></p>'
                    ],
                    'zh' => [
                        'subject' => '您被邀请加入 {workspace_name}',
                        'content' => '<h2>您被邀请加入工作空间！</h2><p>您好 <strong>{user_name}</strong>，</p><p><strong>{invited_by_name}</strong> 邀请您加入工作空间 "<strong>{workspace_name}</strong>"。</p><p><strong>工作空间：</strong> {workspace_name}</p><p><strong>邀请人：</strong> {invited_by_name}</p><p><strong>角色：</strong> {role}</p><p>点击下面的按钮接受邀请：</p><p><a href="{invitation_link}" style="background-color: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; display: inline-block; font-weight: bold;">接受邀请</a></p><p>接受后，您可以开始与团队成员在此工作空间中协作。</p><p>此致敬礼，<br><strong>{app_name} 团队</strong></p>'
                    ]
                ]
            ],
            [
                'name' => 'Project Assignment',
                'from' => 'Support Team',
                'translations' => [
                    'en' => [
                        'subject' => 'You have been assigned to project {project_name} in {workspace_name}',
                        'content' => '<h2>You have been assigned to a project!</h2><p>Hello <strong>{assigned_user_name}</strong>,</p><p>You have been assigned by <strong>{assigned_by_name}</strong> to the project "<strong>{project_name}</strong>" as a <strong>{role}</strong>.</p><p><strong>Project:</strong> {project_name}</p><p><strong>Your Role:</strong> {role}</p><p><strong>Assigned By:</strong> {assigned_by_name}</p><p><strong>Description:</strong> {project_description}</p><p>You can now access this project and start collaborating with your team.</p><p>Best regards,<br><strong>The {company_name} Team</strong></p>'
                    ],
                    'es' => [
                        'subject' => 'Se le ha asignado al proyecto {project_name} en {workspace_name}',
                        'content' => '<h2>¡Se le ha asignado a un proyecto!</h2><p>Hola <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> le ha asignado al proyecto "<strong>{project_name}</strong>" como <strong>{role}</strong>.</p><p><strong>Proyecto:</strong> {project_name}</p><p><strong>Espacio de trabajo:</strong> {workspace_name}</p><p><strong>Su rol:</strong> {role}</p><p><strong>Asignado por:</strong> {assigned_by_name}</p><p><strong>Descripción:</strong> {project_description}</p><p>Ahora puede acceder a este proyecto y comenzar a colaborar con su equipo.</p><p>Saludos cordiales,<br><strong>El equipo de {app_name}</strong></p>'
                    ],
                    'ar' => [
                        'subject' => 'تم تعيينك إلى المشروع {project_name} في {workspace_name}',
                        'content' => '<h2>تم تعيينك إلى مشروع!</h2><p>مرحباً <strong>{user_name}</strong>,</p><p>لقد قام <strong>{assigned_by_name}</strong> بتعيينك في المشروع "<strong>{project_name}</strong>" كـ<strong>{role}</strong>.</p><p><strong>المشروع:</strong> {project_name}</p><p><strong>مساحة العمل:</strong> {workspace_name}</p><p><strong>دورك:</strong> {role}</p><p><strong>المعين بواسطة:</strong> {assigned_by_name}</p><p><strong>الوصف:</strong> {project_description}</p><p>يمكنك الآن الوصول إلى هذا المشروع والبدء في التعاون مع فريقك.</p><p>مع أطيب التحيات,<br><strong>فريق {app_name}</strong></p>'
                    ],
                    'da' => [
                        'subject' => 'Du er blevet tildelt projektet {project_name} i {workspace_name}',
                        'content' => '<h2>Du er blevet tildelt et projekt!</h2><p>Hej <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> har tildelt dig til projektet "<strong>{project_name}</strong>" som <strong>{role}</strong>.</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Arbejdsområde:</strong> {workspace_name}</p><p><strong>Din rolle:</strong> {role}</p><p><strong>Tildelt af:</strong> {assigned_by_name}</p><p><strong>Beskrivelse:</strong> {project_description}</p><p>Du kan nu få adgang til dette projekt og begynde at samarbejde med dit team.</p><p>Venlig hilsen,<br><strong>{app_name} Teamet</strong></p>'
                    ],
                    'de' => [
                        'subject' => 'Sie wurden dem Projekt {project_name} in {workspace_name} zugewiesen',
                        'content' => '<h2>Ihnen wurde ein Projekt zugewiesen!</h2><p>Hallo <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> hat Sie dem Projekt "<strong>{project_name}</strong>" als <strong>{role}</strong> zugewiesen.</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Arbeitsbereich:</strong> {workspace_name}</p><p><strong>Ihre Rolle:</strong> {role}</p><p><strong>Zugewiesen von:</strong> {assigned_by_name}</p><p><strong>Beschreibung:</strong> {project_description}</p><p>Sie können nun auf dieses Projekt zugreifen und mit Ihrem Team zusammenarbeiten.</p><p>Mit freundlichen Grüßen,<br><strong>Das {app_name} Team</strong></p>'
                    ],
                    'fr' => [
                        'subject' => 'Vous avez été assigné au projet {project_name} dans {workspace_name}',
                        'content' => '<h2>Vous avez été assigné à un projet !</h2><p>Bonjour <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> vous a assigné au projet "<strong>{project_name}</strong>" en tant que <strong>{role}</strong>.</p><p><strong>Projet :</strong> {project_name}</p><p><strong>Espace de travail :</strong> {workspace_name}</p><p><strong>Votre rôle :</strong> {role}</p><p><strong>Assigné par :</strong> {assigned_by_name}</p><p><strong>Description :</strong> {project_description}</p><p>Vous pouvez maintenant accéder à ce projet et commencer à collaborer avec votre équipe.</p><p>Cordialement,<br><strong>L\'équipe {app_name}</strong></p>'
                    ],
                    'he' => [
                        'subject' => 'הוקצתה לך לפרויקט {project_name} ב-{workspace_name}',
                        'content' => '<h2>הוקצתה לך לפרויקט!</h2><p>שלום <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> הקצה אותך לפרויקט "<strong>{project_name}</strong>" בתפקיד <strong>{role}</strong>.</p><p><strong>פרויקט:</strong> {project_name}</p><p><strong>סביבת עבודה:</strong> {workspace_name}</p><p><strong>תפקידך:</strong> {role}</p><p><strong>הוקצה על ידי:</strong> {assigned_by_name}</p><p><strong>תיאור:</strong> {project_description}</p><p>כעת תוכל לגשת לפרויקט זה ולהתחיל לשתף פעולה עם הצוות שלך.</p><p>בברכה,<br><strong>צוות {app_name}</strong></p>'
                    ],
                    'it' => [
                        'subject' => 'Sei stato assegnato al progetto {project_name} in {workspace_name}',
                        'content' => '<h2>Sei stato assegnato a un progetto!</h2><p>Ciao <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> ti ha assegnato al progetto "<strong>{project_name}</strong>" come <strong>{role}</strong>.</p><p><strong>Progetto:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Il tuo ruolo:</strong> {role}</p><p><strong>Assegnato da:</strong> {assigned_by_name}</p><p><strong>Descrizione:</strong> {project_description}</p><p>Ora puoi accedere a questo progetto e iniziare a collaborare con il tuo team.</p><p>Cordiali saluti,<br><strong>Il team di {app_name}</strong></p>'
                    ],
                    'ja' => [
                        'subject' => '{workspace_name}でプロジェクト {project_name} に割り当てられました',
                        'content' => '<h2>プロジェクトが割り当てられました！</h2><p>こんにちは <strong>{user_name}</strong> さん、</p><p><strong>{assigned_by_name}</strong> があなたをプロジェクト "<strong>{project_name}</strong>" に <strong>{role}</strong> として割り当てました。</p><p><strong>プロジェクト:</strong> {project_name}</p><p><strong>ワークスペース:</strong> {workspace_name}</p><p><strong>あなたの役割:</strong> {role}</p><p><strong>割り当て者:</strong> {assigned_by_name}</p><p><strong>説明:</strong> {project_description}</p><p>このプロジェクトにアクセスして、チームと協力を開始できます。</p><p>よろしくお願いいたします,<br><strong>{app_name} チーム</strong></p>'
                    ],
                    'nl' => [
                        'subject' => 'U bent toegewezen aan project {project_name} in {workspace_name}',
                        'content' => '<h2>U bent toegewezen aan een project!</h2><p>Hallo <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> heeft u toegewezen aan het project "<strong>{project_name}</strong>" als <strong>{role}</strong>.</p><p><strong>Project:</strong> {project_name}</p><p><strong>Werkruimte:</strong> {workspace_name}</p><p><strong>Uw rol:</strong> {role}</p><p><strong>Toegewezen door:</strong> {assigned_by_name}</p><p><strong>Beschrijving:</strong> {project_description}</p><p>U kunt nu toegang krijgen tot dit project en beginnen samen te werken met uw team.</p><p>Met vriendelijke groet,<br><strong>Het {app_name} Team</strong></p>'
                    ],
                    'pl' => [
                        'subject' => 'Zostałeś przypisany do projektu {project_name} w {workspace_name}',
                        'content' => '<h2>Zostałeś przypisany do projektu!</h2><p>Cześć <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> przypisał Cię do projektu "<strong>{project_name}</strong>" jako <strong>{role}</strong>.</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Twoja rola:</strong> {role}</p><p><strong>Przypisane przez:</strong> {assigned_by_name}</p><p><strong>Opis:</strong> {project_description}</p><p>Możesz teraz uzyskać dostęp do tego projektu i rozpocząć współpracę z zespołem.</p><p>Z poważaniem,<br><strong>Zespół {app_name}</strong></p>'
                    ],
                    'pt' => [
                        'subject' => 'Você foi designado ao projeto {project_name} em {workspace_name}',
                        'content' => '<h2>Você foi designado a um projeto!</h2><p>Olá <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> designou você ao projeto "<strong>{project_name}</strong>" como <strong>{role}</strong>.</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Seu papel:</strong> {role}</p><p><strong>Designado por:</strong> {assigned_by_name}</p><p><strong>Descrição:</strong> {project_description}</p><p>Agora você pode acessar este projeto e começar a colaborar com sua equipe.</p><p>Atenciosamente,<br><strong>A equipe {app_name}</strong></p>'
                    ],
                    'pt-BR' => [
                        'subject' => 'Você foi designado ao projeto {project_name} em {workspace_name}',
                        'content' => '<h2>Você foi designado a um projeto!</h2><p>Olá <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> designou você ao projeto "<strong>{project_name}</strong>" como <strong>{role}</strong>.</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Seu papel:</strong> {role}</p><p><strong>Designado por:</strong> {assigned_by_name}</p><p><strong>Descrição:</strong> {project_description}</p><p>Agora você pode acessar este projeto e começar a colaborar com sua equipe.</p><p>Atenciosamente,<br><strong>A equipe {app_name}</strong></p>'
                    ],
                    'ru' => [
                        'subject' => 'Вам назначен проект {project_name} в {workspace_name}',
                        'content' => '<h2>Вам назначен проект!</h2><p>Здравствуйте <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> назначил вас в проект "<strong>{project_name}</strong>" как <strong>{role}</strong>.</p><p><strong>Проект:</strong> {project_name}</p><p><strong>Рабочее пространство:</strong> {workspace_name}</p><p><strong>Ваша роль:</strong> {role}</p><p><strong>Назначено:</strong> {assigned_by_name}</p><p><strong>Описание:</strong> {project_description}</p><p>Теперь вы можете получить доступ к этому проекту и начать сотрудничество с вашей командой.</p><p>С уважением,<br><strong>Команда {app_name}</strong></p>'
                    ],
                    'tr' => [
                        'subject' => '{workspace_name} içinde {project_name} projesine atandınız',
                        'content' => '<h2>Bir projeye atandınız!</h2><p>Merhaba <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> sizi "<strong>{project_name}</strong>" projesine <strong>{role}</strong> olarak atadı.</p><p><strong>Proje:</strong> {project_name}</p><p><strong>Çalışma Alanı:</strong> {workspace_name}</p><p><strong>Rolünüz:</strong> {role}</p><p><strong>Atayan:</strong> {assigned_by_name}</p><p><strong>Açıklama:</strong> {project_description}</p><p>Artık bu projeye erişebilir ve ekibinizle iş birliğine başlayabilirsiniz.</p><p>Saygılarımızla,<br><strong>{app_name} Ekibi</strong></p>'
                    ],
                    'zh' => [
                        'subject' => '您已被分配到 {workspace_name} 中的项目 {project_name}',
                        'content' => '<h2>您已被分配到一个项目！</h2><p>您好 <strong>{user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> 已将您分配到项目 "<strong>{project_name}</strong>"，角色为 <strong>{role}</strong>。</p><p><strong>项目:</strong> {project_name}</p><p><strong>工作区:</strong> {workspace_name}</p><p><strong>您的角色:</strong> {role}</p><p><strong>分配人:</strong> {assigned_by_name}</p><p><strong>描述:</strong> {project_description}</p><p>您现在可以访问该项目并开始与团队合作。</p><p>此致敬礼,<br><strong>{app_name} 团队</strong></p>'
                    ],
                ]
            ],
            [
                'name' => 'Task Assignment',
                'from' => 'Support Team',
                'translations' => [
                    'en' => [
                        'subject' => 'You have been assigned to a task in {project_name}',
                        'content' => '<h2>You have been assigned to a task!</h2><p>Hello <strong>{assigned_user_name}</strong>,</p><p>You have been assigned by <strong>{assigned_by_name}</strong> to the task "<strong>{task_title}</strong>" in project <strong>{project_name}</strong>.</p><p><strong>Task:</strong> {task_title}</p><p><strong>Project:</strong> {project_name}</p><p><strong>Priority:</strong> {task_priority}</p><p><strong>Start Date:</strong> {start_date}</p><p><strong>End Date:</strong> {end_date}</p><p><strong>Assigned By:</strong> {assigned_by_name}</p><p><strong>Description:</strong> {task_description}</p><p>You can now access this task and start working on it. Please log in to your account to view the task details.</p><p>Best regards,<br><strong>The {company_name} Team</strong></p>'
                    ],
                    'es' => [
                        'subject' => 'Se le ha asignado una tarea en {project_name}',
                        'content' => '<h2>¡Se le ha asignado una tarea!</h2><p>Hola <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> le ha asignado la tarea "<strong>{task_title}</strong>" en el proyecto <strong>{project_name}</strong>.</p><p><strong>Tarea:</strong> {task_title}</p><p><strong>Proyecto:</strong> {project_name}</p><p><strong>Espacio de trabajo:</strong> {project_name}</p><p><strong>Prioridad:</strong> {task_priority}</p><p><strong>Fecha de inicio:</strong> {start_date}</p><p><strong>Fecha de finalización:</strong> {end_date}</p><p><strong>Asignado por:</strong> {assigned_by_name}</p><p><strong>Descripción:</strong> {task_description}</p><p>Ahora puede acceder a esta tarea y comenzar a trabajar en ella. Inicie sesión en su cuenta para ver los detalles.</p><p>Saludos cordiales,<br><strong>El equipo de {company_name}</strong></p>'
                    ],
                    'ar' => [
                        'subject' => 'تم تعيينك لمهمة في {project_name}',
                        'content' => '<h2>تم تعيينك لمهمة!</h2><p>مرحباً <strong>{assigned_user_name}</strong>,</p><p>لقد تم تعيينك من قبل <strong>{assigned_by_name}</strong> للمهمة "<strong>{task_title}</strong>" في المشروع <strong>{project_name}</strong>.</p><p><strong>المهمة:</strong> {task_title}</p><p><strong>المشروع:</strong> {project_name}</p><p><strong>مساحة العمل:</strong> {project_name}</p><p><strong>الأولوية:</strong> {task_priority}</p><p><strong>تاريخ البدء:</strong> {start_date}</p><p><strong>تاريخ الانتهاء:</strong> {end_date}</p><p><strong>المُعين بواسطة:</strong> {assigned_by_name}</p><p><strong>الوصف:</strong> {task_description}</p><p>يمكنك الآن الوصول إلى هذه المهمة والبدء بالعمل عليها. يرجى تسجيل الدخول إلى حسابك لعرض التفاصيل.</p><p>مع أطيب التحيات,<br><strong>فريق {company_name}</strong></p>'
                    ],
                    'da' => [
                        'subject' => 'Du er blevet tildelt en opgave i {project_name}',
                        'content' => '<h2>Du er blevet tildelt en opgave!</h2><p>Hej <strong>{assigned_user_name}</strong>,</p><p>Du er blevet tildelt af <strong>{assigned_by_name}</strong> til opgaven "<strong>{task_title}</strong>" i projektet <strong>{project_name}</strong>.</p><p><strong>Opgave:</strong> {task_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Arbejdsområde:</strong> {project_name}</p><p><strong>Prioritet:</strong> {task_priority}</p><p><strong>Startdato:</strong> {start_date}</p><p><strong>Slutdato:</strong> {end_date}</p><p><strong>Tildelt af:</strong> {assigned_by_name}</p><p><strong>Beskrivelse:</strong> {task_description}</p><p>Du kan nu få adgang til denne opgave og begynde at arbejde på den. Log ind for at se detaljerne.</p><p>Venlig hilsen,<br><strong>{company_name} Teamet</strong></p>'
                    ],
                    'de' => [
                        'subject' => 'Ihnen wurde eine Aufgabe in {project_name} zugewiesen',
                        'content' => '<h2>Ihnen wurde eine Aufgabe zugewiesen!</h2><p>Hallo <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> hat Ihnen die Aufgabe "<strong>{task_title}</strong>" im Projekt <strong>{project_name}</strong> zugewiesen.</p><p><strong>Aufgabe:</strong> {task_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Arbeitsbereich:</strong> {project_name}</p><p><strong>Priorität:</strong> {task_priority}</p><p><strong>Startdatum:</strong> {start_date}</p><p><strong>Enddatum:</strong> {end_date}</p><p><strong>Zugewiesen von:</strong> {assigned_by_name}</p><p><strong>Beschreibung:</strong> {task_description}</p><p>Sie können nun auf diese Aufgabe zugreifen und mit der Arbeit beginnen. Bitte melden Sie sich an, um die Details zu sehen.</p><p>Mit freundlichen Grüßen,<br><strong>Das {company_name} Team</strong></p>'
                    ],
                    'fr' => [
                        'subject' => 'Une tâche vous a été assignée dans {project_name}',
                        'content' => '<h2>Une tâche vous a été assignée !</h2><p>Bonjour <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> vous a assigné la tâche "<strong>{task_title}</strong>" dans le projet <strong>{project_name}</strong>.</p><p><strong>Tâche :</strong> {task_title}</p><p><strong>Projet :</strong> {project_name}</p><p><strong>Espace de travail :</strong> {project_name}</p><p><strong>Priorité :</strong> {task_priority}</p><p><strong>Date de début :</strong> {start_date}</p><p><strong>Date de fin :</strong> {end_date}</p><p><strong>Assigné par :</strong> {assigned_by_name}</p><p><strong>Description :</strong> {task_description}</p><p>Vous pouvez maintenant accéder à cette tâche et commencer à travailler dessus. Connectez-vous à votre compte pour voir les détails.</p><p>Cordialement,<br><strong>L\'équipe {company_name}</strong></p>'
                    ],
                    'he' => [
                        'subject' => 'הוקצתה לך משימה ב-{project_name}',
                        'content' => '<h2>הוקצתה לך משימה!</h2><p>שלום <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> הקצה לך את המשימה "<strong>{task_title}</strong>" בפרויקט <strong>{project_name}</strong>.</p><p><strong>משימה:</strong> {task_title}</p><p><strong>פרויקט:</strong> {project_name}</p><p><strong>סביבת עבודה:</strong> {project_name}</p><p><strong>עדיפות:</strong> {task_priority}</p><p><strong>תאריך התחלה:</strong> {start_date}</p><p><strong>תאריך סיום:</strong> {end_date}</p><p><strong>הוקצה על ידי:</strong> {assigned_by_name}</p><p><strong>תיאור:</strong> {task_description}</p><p>כעת תוכל לגשת למשימה זו ולהתחיל לעבוד עליה. אנא היכנס לחשבונך כדי לצפות בפרטי המשימה.</p><p>בברכה,<br><strong>צוות {company_name}</strong></p>'
                    ],
                    'it' => [
                        'subject' => 'Ti è stato assegnato un compito in {project_name}',
                        'content' => '<h2>Ti è stato assegnato un compito!</h2><p>Ciao <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> ti ha assegnato il compito "<strong>{task_title}</strong>" nel progetto <strong>{project_name}</strong>.</p><p><strong>Compito:</strong> {task_title}</p><p><strong>Progetto:</strong> {project_name}</p><p><strong>Workspace:</strong> {project_name}</p><p><strong>Priorità:</strong> {task_priority}</p><p><strong>Data di inizio:</strong> {start_date}</p><p><strong>Data di fine:</strong> {end_date}</p><p><strong>Assegnato da:</strong> {assigned_by_name}</p><p><strong>Descrizione:</strong> {task_description}</p><p>Ora puoi accedere a questo compito e iniziare a lavorarci. Accedi al tuo account per vedere i dettagli.</p><p>Cordiali saluti,<br><strong>Il team di {company_name}</strong></p>'
                    ],
                    'ja' => [
                        'subject' => '{project_name}でタスクが割り当てられました',
                        'content' => '<h2>タスクが割り当てられました！</h2><p>こんにちは <strong>{assigned_user_name}</strong> さん、</p><p><strong>{assigned_by_name}</strong> がプロジェクト <strong>{project_name}</strong> 内のタスク "<strong>{task_title}</strong>" をあなたに割り当てました。</p><p><strong>タスク:</strong> {task_title}</p><p><strong>プロジェクト:</strong> {project_name}</p><p><strong>ワークスペース:</strong> {project_name}</p><p><strong>優先度:</strong> {task_priority}</p><p><strong>開始日:</strong> {start_date}</p><p><strong>終了日:</strong> {end_date}</p><p><strong>割り当て者:</strong> {assigned_by_name}</p><p><strong>説明:</strong> {task_description}</p><p>このタスクにアクセスして作業を開始できます。詳細を見るにはアカウントにログインしてください。</p><p>よろしくお願いいたします,<br><strong>{company_name} チーム</strong></p>'
                    ],
                    'nl' => [
                        'subject' => 'U bent toegewezen aan een taak in {project_name}',
                        'content' => '<h2>U bent toegewezen aan een taak!</h2><p>Hallo <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> heeft u toegewezen aan de taak "<strong>{task_title}</strong>" in het project <strong>{project_name}</strong>.</p><p><strong>Taak:</strong> {task_title}</p><p><strong>Project:</strong> {project_name}</p><p><strong>Werkruimte:</strong> {project_name}</p><p><strong>Prioriteit:</strong> {task_priority}</p><p><strong>Startdatum:</strong> {start_date}</p><p><strong>Einddatum:</strong> {end_date}</p><p><strong>Toegewezen door:</strong> {assigned_by_name}</p><p><strong>Beschrijving:</strong> {task_description}</p><p>U kunt nu toegang krijgen tot deze taak en ermee aan de slag gaan. Log in om de details te bekijken.</p><p>Met vriendelijke groet,<br><strong>Het {company_name} Team</strong></p>'
                    ],
                    'pl' => [
                        'subject' => 'Przypisano Ci zadanie w {project_name}',
                        'content' => '<h2>Przypisano Ci zadanie!</h2><p>Cześć <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> przypisał Ci zadanie "<strong>{task_title}</strong>" w projekcie <strong>{project_name}</strong>.</p><p><strong>Zadanie:</strong> {task_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Workspace:</strong> {project_name}</p><p><strong>Priorytet:</strong> {task_priority}</p><p><strong>Data rozpoczęcia:</strong> {start_date}</p><p><strong>Data zakończenia:</strong> {end_date}</p><p><strong>Przypisane przez:</strong> {assigned_by_name}</p><p><strong>Opis:</strong> {task_description}</p><p>Możesz teraz uzyskać dostęp do tego zadania i rozpocząć pracę nad nim. Zaloguj się, aby zobaczyć szczegóły.</p><p>Z poważaniem,<br><strong>Zespół {company_name}</strong></p>'
                    ],
                    'pt' => [
                        'subject' => 'Você foi designado para uma tarefa em {project_name}',
                        'content' => '<h2>Você foi designado para uma tarefa!</h2><p>Olá <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> atribuiu a você a tarefa "<strong>{task_title}</strong>" no projeto <strong>{project_name}</strong>.</p><p><strong>Tarefa:</strong> {task_title}</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Workspace:</strong> {project_name}</p><p><strong>Prioridade:</strong> {task_priority}</p><p><strong>Data de início:</strong> {start_date}</p><p><strong>Data de término:</strong> {end_date}</p><p><strong>Atribuído por:</strong> {assigned_by_name}</p><p><strong>Descrição:</strong> {task_description}</p><p>Agora você pode acessar esta tarefa e começar a trabalhar nela. Faça login para ver os detalhes.</p><p>Atenciosamente,<br><strong>A equipe {company_name}</strong></p>'
                    ],
                    'pt-BR' => [
                        'subject' => 'Você foi designado para uma tarefa em {project_name}',
                        'content' => '<h2>Você foi designado para uma tarefa!</h2><p>Olá <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> atribuiu a você a tarefa "<strong>{task_title}</strong>" no projeto <strong>{project_name}</strong>.</p><p><strong>Tarefa:</strong> {task_title}</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Workspace:</strong> {project_name}</p><p><strong>Prioridade:</strong> {task_priority}</p><p><strong>Data de início:</strong> {start_date}</p><p><strong>Data de término:</strong> {end_date}</p><p><strong>Atribuído por:</strong> {assigned_by_name}</p><p><strong>Descrição:</strong> {task_description}</p><p>Agora você pode acessar esta tarefa e começar a trabalhar nela. Faça login para ver os detalhes.</p><p>Atenciosamente,<br><strong>A equipe {company_name}</strong></p>'
                    ],
                    'ru' => [
                        'subject' => 'Вам назначено задание в {project_name}',
                        'content' => '<h2>Вам назначено задание!</h2><p>Здравствуйте <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> назначил вам задание "<strong>{task_title}</strong>" в проекте <strong>{project_name}</strong>.</p><p><strong>Задание:</strong> {task_title}</p><p><strong>Проект:</strong> {project_name}</p><p><strong>Рабочее пространство:</strong> {project_name}</p><p><strong>Приоритет:</strong> {task_priority}</p><p><strong>Дата начала:</strong> {start_date}</p><p><strong>Дата окончания:</strong> {end_date}</p><p><strong>Назначено:</strong> {assigned_by_name}</p><p><strong>Описание:</strong> {task_description}</p><p>Теперь вы можете получить доступ к этому заданию и начать работу над ним. Войдите в свой аккаунт, чтобы просмотреть детали.</p><p>С уважением,<br><strong>Команда {company_name}</strong></p>'
                    ],
                    'tr' => [
                        'subject' => '{project_name} içinde size bir görev atandı',
                        'content' => '<h2>Size bir görev atandı!</h2><p>Merhaba <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> size "<strong>{task_title}</strong>" görevini <strong>{project_name}</strong> projesinde atadı.</p><p><strong>Görev:</strong> {task_title}</p><p><strong>Proje:</strong> {project_name}</p><p><strong>Çalışma Alanı:</strong> {project_name}</p><p><strong>Öncelik:</strong> {task_priority}</p><p><strong>Başlangıç Tarihi:</strong> {start_date}</p><p><strong>Bitiş Tarihi:</strong> {end_date}</p><p><strong>Atayan:</strong> {assigned_by_name}</p><p><strong>Açıklama:</strong> {task_description}</p><p>Artık bu göreve erişebilir ve çalışmaya başlayabilirsiniz. Ayrıntıları görmek için hesabınıza giriş yapın.</p><p>Saygılarımızla,<br><strong>{company_name} Ekibi</strong></p>'
                    ],
                    'zh' => [
                        'subject' => '您已被分配任务于 {project_name}',
                        'content' => '<h2>您已被分配任务！</h2><p>您好 <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> 已将任务 "<strong>{task_title}</strong>" 分配给您，所在项目为 <strong>{project_name}</strong>。</p><p><strong>任务:</strong> {task_title}</p><p><strong>项目:</strong> {project_name}</p><p><strong>工作区:</strong> {project_name}</p><p><strong>优先级:</strong> {task_priority}</p><p><strong>开始日期:</strong> {start_date}</p><p><strong>结束日期:</strong> {end_date}</p><p><strong>分配人:</strong> {assigned_by_name}</p><p><strong>描述:</strong> {task_description}</p><p>您现在可以访问此任务并开始工作。请登录您的账户查看详情。</p><p>此致敬礼,<br><strong>{company_name} 团队</strong></p>'
                    ],
                ]
            ],
            [
                'name' => 'Bug Assignment',
                'from' => 'Support Team',
                'translations' => [
                    'en' => [
                        'subject' => 'You have been assigned to a bug in {workspace_name}',
                        'content' => '<h2>You have been assigned to a bug!</h2><p>Hello <strong>{assigned_user_name}</strong>,</p><p>You have been assigned by <strong>{assigned_by_name}</strong> to the bug "<strong>{bug_title}</strong>" in project <strong>{project_name}</strong>.</p><p><strong>Bug:</strong> {bug_title}</p><p><strong>Project:</strong> {project_name}</p><p><strong>Priority:</strong> {bug_priority}</p><p><strong>Severity:</strong> {bug_severity}</p><p><strong>Start Date:</strong> {start_date}</p><p><strong>End Date:</strong> {end_date}</p><p><strong>Assigned By:</strong> {assigned_by_name}</p><p><strong>Description:</strong> {bug_description}</p><p>You can now access this bug and start working on it. Please log in to your account to view the bug details.</p><p>Best regards,<br><strong>The {company_name} Team</strong></p>'
                    ],
                    'es' => [
                        'subject' => 'Se le ha asignado un error en {workspace_name}',
                        'content' => '<h2>¡Se le ha asignado un error!</h2><p>Hola <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> le ha asignado al error "<strong>{bug_title}</strong>" en el proyecto <strong>{project_name}</strong>.</p><p><strong>Error:</strong> {bug_title}</p><p><strong>Proyecto:</strong> {project_name}</p><p><strong>Espacio de trabajo:</strong> {workspace_name}</p><p><strong>Prioridad:</strong> {bug_priority}</p><p><strong>Severidad:</strong> {bug_severity}</p><p><strong>Fecha de inicio:</strong> {start_date}</p><p><strong>Fecha de finalización:</strong> {end_date}</p><p><strong>Asignado por:</strong> {assigned_by_name}</p><p><strong>Descripción:</strong> {bug_description}</p><p>Ahora puede acceder a este error y comenzar a trabajar en él. Por favor, inicie sesión en su cuenta para ver los detalles del error.</p><p>Saludos cordiales,<br><strong>El equipo de {company_name}</strong></p>'
                    ],
                    'ar' => [
                        'subject' => 'تم تعيينك على خطأ في {workspace_name}',
                        'content' => '<h2>تم تعيينك على خطأ!</h2><p>مرحباً <strong>{assigned_user_name}</strong>,</p><p>لقد قام <strong>{assigned_by_name}</strong> بتعيينك على الخطأ "<strong>{bug_title}</strong>" في المشروع <strong>{project_name}</strong>.</p><p><strong>الخطأ:</strong> {bug_title}</p><p><strong>المشروع:</strong> {project_name}</p><p><strong>مساحة العمل:</strong> {workspace_name}</p><p><strong>الأولوية:</strong> {bug_priority}</p><p><strong>الخطورة:</strong> {bug_severity}</p><p><strong>تاريخ البداية:</strong> {start_date}</p><p><strong>تاريخ الانتهاء:</strong> {end_date}</p><p><strong>المعين بواسطة:</strong> {assigned_by_name}</p><p><strong>الوصف:</strong> {bug_description}</p><p>يمكنك الآن الوصول إلى هذا الخطأ وبدء العمل عليه. الرجاء تسجيل الدخول لمشاهدة تفاصيل الخطأ.</p><p>مع أطيب التحيات,<br><strong>فريق {company_name}</strong></p>'
                    ],
                    'da' => [
                        'subject' => 'Du er blevet tildelt en fejl i {workspace_name}',
                        'content' => '<h2>Du er blevet tildelt en fejl!</h2><p>Hej <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> har tildelt dig fejlen "<strong>{bug_title}</strong>" i projektet <strong>{project_name}</strong>.</p><p><strong>Fejl:</strong> {bug_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Arbejdsområde:</strong> {workspace_name}</p><p><strong>Prioritet:</strong> {bug_priority}</p><p><strong>Alvorlighed:</strong> {bug_severity}</p><p><strong>Startdato:</strong> {start_date}</p><p><strong>Slutdato:</strong> {end_date}</p><p><strong>Tildelt af:</strong> {assigned_by_name}</p><p><strong>Beskrivelse:</strong> {bug_description}</p><p>Du kan nu få adgang til denne fejl og begynde at arbejde på den. Log ind på din konto for at se fejlens detaljer.</p><p>Venlig hilsen,<br><strong>{company_name} Teamet</strong></p>'
                    ],
                    'de' => [
                        'subject' => 'Sie wurden einem Fehler in {workspace_name} zugewiesen',
                        'content' => '<h2>Sie wurden einem Fehler zugewiesen!</h2><p>Hallo <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> hat Sie dem Fehler "<strong>{bug_title}</strong>" im Projekt <strong>{project_name}</strong> zugewiesen.</p><p><strong>Fehler:</strong> {bug_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Arbeitsbereich:</strong> {workspace_name}</p><p><strong>Priorität:</strong> {bug_priority}</p><p><strong>Schwere:</strong> {bug_severity}</p><p><strong>Startdatum:</strong> {start_date}</p><p><strong>Enddatum:</strong> {end_date}</p><p><strong>Zugewiesen von:</strong> {assigned_by_name}</p><p><strong>Beschreibung:</strong> {bug_description}</p><p>Sie können nun auf diesen Fehler zugreifen und daran arbeiten. Bitte melden Sie sich an, um die Fehlerdetails anzuzeigen.</p><p>Mit freundlichen Grüßen,<br><strong>Das {company_name} Team</strong></p>'
                    ],
                    'fr' => [
                        'subject' => 'Vous avez été assigné à un bug dans {workspace_name}',
                        'content' => '<h2>Vous avez été assigné à un bug !</h2><p>Bonjour <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> vous a assigné au bug "<strong>{bug_title}</strong>" dans le projet <strong>{project_name}</strong>.</p><p><strong>Bug :</strong> {bug_title}</p><p><strong>Projet :</strong> {project_name}</p><p><strong>Espace de travail :</strong> {workspace_name}</p><p><strong>Priorité :</strong> {bug_priority}</p><p><strong>Gravité :</strong> {bug_severity}</p><p><strong>Date de début :</strong> {start_date}</p><p><strong>Date de fin :</strong> {end_date}</p><p><strong>Assigné par :</strong> {assigned_by_name}</p><p><strong>Description :</strong> {bug_description}</p><p>Vous pouvez maintenant accéder à ce bug et commencer à travailler dessus. Veuillez vous connecter pour voir les détails du bug.</p><p>Cordialement,<br><strong>L\'équipe {company_name}</strong></p>'
                    ],
                    'he' => [
                        'subject' => 'הוקצתה לך תקלה ב-{workspace_name}',
                        'content' => '<h2>הוקצתה לך תקלה!</h2><p>שלום <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> הקצה אותך לתקלה "<strong>{bug_title}</strong>" בפרויקט <strong>{project_name}</strong>.</p><p><strong>תקלה:</strong> {bug_title}</p><p><strong>פרויקט:</strong> {project_name}</p><p><strong>סביבת עבודה:</strong> {workspace_name}</p><p><strong>עדיפות:</strong> {bug_priority}</p><p><strong>חומרה:</strong> {bug_severity}</p><p><strong>תאריך התחלה:</strong> {start_date}</p><p><strong>תאריך סיום:</strong> {end_date}</p><p><strong>הוקצה על ידי:</strong> {assigned_by_name}</p><p><strong>תיאור:</strong> {bug_description}</p><p>כעת תוכל לגשת לתקלה זו ולהתחיל לעבוד עליה. אנא היכנס לחשבונך כדי לראות את פרטי התקלה.</p><p>בברכה,<br><strong>צוות {company_name}</strong></p>'
                    ],
                    'it' => [
                        'subject' => 'Sei stato assegnato a un bug in {workspace_name}',
                        'content' => '<h2>Sei stato assegnato a un bug!</h2><p>Ciao <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> ti ha assegnato al bug "<strong>{bug_title}</strong>" nel progetto <strong>{project_name}</strong>.</p><p><strong>Bug:</strong> {bug_title}</p><p><strong>Progetto:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Priorità:</strong> {bug_priority}</p><p><strong>Gravità:</strong> {bug_severity}</p><p><strong>Data inizio:</strong> {start_date}</p><p><strong>Data fine:</strong> {end_date}</p><p><strong>Assegnato da:</strong> {assigned_by_name}</p><p><strong>Descrizione:</strong> {bug_description}</p><p>Ora puoi accedere a questo bug e iniziare a lavorarci. Effettua il login per visualizzare i dettagli del bug.</p><p>Cordiali saluti,<br><strong>Il team {company_name}</strong></p>'
                    ],
                    'ja' => [
                        'subject' => '{workspace_name} のバグに割り当てられました',
                        'content' => '<h2>バグに割り当てられました！</h2><p>こんにちは <strong>{assigned_user_name}</strong> さん、</p><p><strong>{assigned_by_name}</strong> がプロジェクト <strong>{project_name}</strong> のバグ "<strong>{bug_title}</strong>" に割り当てました。</p><p><strong>バグ:</strong> {bug_title}</p><p><strong>プロジェクト:</strong> {project_name}</p><p><strong>ワークスペース:</strong> {workspace_name}</p><p><strong>優先度:</strong> {bug_priority}</p><p><strong>重大度:</strong> {bug_severity}</p><p><strong>開始日:</strong> {start_date}</p><p><strong>終了日:</strong> {end_date}</p><p><strong>割り当て者:</strong> {assigned_by_name}</p><p><strong>説明:</strong> {bug_description}</p><p>このバグにアクセスして作業を開始できます。詳細を見るにはログインしてください。</p><p>よろしくお願いいたします,<br><strong>{company_name} チーム</strong></p>'
                    ],
                    'nl' => [
                        'subject' => 'U bent toegewezen aan een bug in {workspace_name}',
                        'content' => '<h2>U bent toegewezen aan een bug!</h2><p>Hallo <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> heeft u toegewezen aan de bug "<strong>{bug_title}</strong>" in project <strong>{project_name}</strong>.</p><p><strong>Bug:</strong> {bug_title}</p><p><strong>Project:</strong> {project_name}</p><p><strong>Werkruimte:</strong> {workspace_name}</p><p><strong>Prioriteit:</strong> {bug_priority}</p><p><strong>Ernst:</strong> {bug_severity}</p><p><strong>Startdatum:</strong> {start_date}</p><p><strong>Einddatum:</strong> {end_date}</p><p><strong>Toegewezen door:</strong> {assigned_by_name}</p><p><strong>Beschrijving:</strong> {bug_description}</p><p>U kunt nu toegang krijgen tot deze bug en eraan werken. Log in om de bugdetails te bekijken.</p><p>Met vriendelijke groet,<br><strong>Het {company_name} Team</strong></p>'
                    ],
                    'pl' => [
                        'subject' => 'Zostałeś przypisany do błędu w {workspace_name}',
                        'content' => '<h2>Zostałeś przypisany do błędu!</h2><p>Cześć <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> przypisał Cię do błędu "<strong>{bug_title}</strong>" w projekcie <strong>{project_name}</strong>.</p><p><strong>Błąd:</strong> {bug_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Priorytet:</strong> {bug_priority}</p><p><strong>Waga:</strong> {bug_severity}</p><p><strong>Data rozpoczęcia:</strong> {start_date}</p><p><strong>Data zakończenia:</strong> {end_date}</p><p><strong>Przypisane przez:</strong> {assigned_by_name}</p><p><strong>Opis:</strong> {bug_description}</p><p>Możesz teraz uzyskać dostęp do tego błędu i rozpocząć nad nim pracę. Zaloguj się, aby zobaczyć szczegóły błędu.</p><p>Z poważaniem,<br><strong>Zespół {company_name}</strong></p>'
                    ],
                    'pt' => [
                        'subject' => 'Você foi designado para um bug em {workspace_name}',
                        'content' => '<h2>Você foi designado a um bug!</h2><p>Olá <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> designou você para o bug "<strong>{bug_title}</strong>" no projeto <strong>{project_name}</strong>.</p><p><strong>Bug:</strong> {bug_title}</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Prioridade:</strong> {bug_priority}</p><p><strong>Severidade:</strong> {bug_severity}</p><p><strong>Data de início:</strong> {start_date}</p><p><strong>Data de término:</strong> {end_date}</p><p><strong>Designado por:</strong> {assigned_by_name}</p><p><strong>Descrição:</strong> {bug_description}</p><p>Agora você pode acessar este bug e começar a trabalhar nele. Faça login para ver os detalhes do bug.</p><p>Atenciosamente,<br><strong>Equipe {company_name}</strong></p>'
                    ],
                    'pt-BR' => [
                        'subject' => 'Você foi designado para um bug em {workspace_name}',
                        'content' => '<h2>Você foi designado a um bug!</h2><p>Olá <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> designou você para o bug "<strong>{bug_title}</strong>" no projeto <strong>{project_name}</strong>.</p><p><strong>Bug:</strong> {bug_title}</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Prioridade:</strong> {bug_priority}</p><p><strong>Severidade:</strong> {bug_severity}</p><p><strong>Data de início:</strong> {start_date}</p><p><strong>Data de término:</strong> {end_date}</p><p><strong>Designado por:</strong> {assigned_by_name}</p><p><strong>Descrição:</strong> {bug_description}</p><p>Agora você pode acessar este bug e começar a trabalhar nele. Faça login para ver os detalhes do bug.</p><p>Atenciosamente,<br><strong>Equipe {company_name}</strong></p>'
                    ],
                    'ru' => [
                        'subject' => 'Вам назначен баг в {workspace_name}',
                        'content' => '<h2>Вам назначен баг!</h2><p>Здравствуйте <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> назначил вас на баг "<strong>{bug_title}</strong>" в проекте <strong>{project_name}</strong>.</p><p><strong>Баг:</strong> {bug_title}</p><p><strong>Проект:</strong> {project_name}</p><p><strong>Рабочее пространство:</strong> {workspace_name}</p><p><strong>Приоритет:</strong> {bug_priority}</p><p><strong>Серьезность:</strong> {bug_severity}</p><p><strong>Дата начала:</strong> {start_date}</p><p><strong>Дата окончания:</strong> {end_date}</p><p><strong>Назначено:</strong> {assigned_by_name}</p><p><strong>Описание:</strong> {bug_description}</p><p>Теперь вы можете получить доступ к этому багу и начать работать над ним. Пожалуйста, войдите в систему, чтобы просмотреть детали бага.</p><p>С уважением,<br><strong>Команда {company_name}</strong></p>'
                    ],
                    'tr' => [
                        'subject' => '{workspace_name} içinde bir hataya atandınız',
                        'content' => '<h2>Bir hataya atandınız!</h2><p>Merhaba <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> sizi proje <strong>{project_name}</strong> içindeki "<strong>{bug_title}</strong>" hatasına atadı.</p><p><strong>Hata:</strong> {bug_title}</p><p><strong>Proje:</strong> {project_name}</p><p><strong>Çalışma Alanı:</strong> {workspace_name}</p><p><strong>Öncelik:</strong> {bug_priority}</p><p><strong>Ciddiyet:</strong> {bug_severity}</p><p><strong>Başlangıç Tarihi:</strong> {start_date}</p><p><strong>Bitiş Tarihi:</strong> {end_date}</p><p><strong>Atayan:</strong> {assigned_by_name}</p><p><strong>Açıklama:</strong> {bug_description}</p><p>Artık bu hataya erişebilir ve üzerinde çalışmaya başlayabilirsiniz. Hata detaylarını görmek için hesabınıza giriş yapın.</p><p>Saygılarımızla,<br><strong>{company_name} Ekibi</strong></p>'
                    ],
                    'zh' => [
                        'subject' => '您已被分配到 {workspace_name} 中的一个错误',
                        'content' => '<h2>您已被分配到一个错误！</h2><p>您好 <strong>{assigned_user_name}</strong>,</p><p><strong>{assigned_by_name}</strong> 已将您分配到项目 <strong>{project_name}</strong> 中的错误 "<strong>{bug_title}</strong>"。</p><p><strong>错误:</strong> {bug_title}</p><p><strong>项目:</strong> {project_name}</p><p><strong>工作区:</strong> {workspace_name}</p><p><strong>优先级:</strong> {bug_priority}</p><p><strong>严重性:</strong> {bug_severity}</p><p><strong>开始日期:</strong> {start_date}</p><p><strong>结束日期:</strong> {end_date}</p><p><strong>分配人:</strong> {assigned_by_name}</p><p><strong>描述:</strong> {bug_description}</p><p>您现在可以访问此错误并开始处理。请登录您的账户查看错误详情。</p><p>此致敬礼,<br><strong>{company_name} 团队</strong></p>'
                    ],
                ]
            ],
            [
                'name' => 'Expense Notification',
                'from' => 'Finance Team',
                'translations' => [
                    'en' => [
                        'subject' => 'New Expense Created: {expense_title}',
                        'content' => '<h2>New Expense Created</h2><p>Hello,</p><p>A new expense has been created in project <strong>{project_name}</strong>.</p><p><strong>Expense:</strong> {expense_title}</p><p><strong>Amount:</strong> {expense_amount}</p><p><strong>Category:</strong> {expense_category}</p><p><strong>Date:</strong> {expense_date}</p><p><strong>Created by:</strong> {created_by_name}</p><p><strong>Description:</strong> {expense_description}</p><p>Best regards,<br><strong>The {app_name} Team</strong></p>'
                    ],
                    'es' => [
                        'subject' => 'Nuevo gasto creado: {expense_title}',
                        'content' => '<h2>Nuevo gasto creado</h2><p>Hola,</p><p>Se ha creado un nuevo gasto en el proyecto <strong>{project_name}</strong>.</p><p><strong>Gasto:</strong> {expense_title}</p><p><strong>Monto:</strong> {expense_amount}</p><p><strong>Categoría:</strong> {expense_category}</p><p><strong>Fecha:</strong> {expense_date}</p><p><strong>Creado por:</strong> {created_by_name}</p><p><strong>Descripción:</strong> {expense_description}</p><p>Saludos cordiales,<br><strong>El equipo de {app_name}</strong></p>'
                    ],
                    'ar' => [
                        'subject' => 'تم إنشاء مصروف جديد: {expense_title}',
                        'content' => '<h2>تم إنشاء مصروف جديد</h2><p>مرحباً،</p><p>تم إنشاء مصروف جديد في المشروع <strong>{project_name}</strong>.</p><p><strong>المصروف:</strong> {expense_title}</p><p><strong>المبلغ:</strong> {expense_amount}</p><p><strong>الفئة:</strong> {expense_category}</p><p><strong>التاريخ:</strong> {expense_date}</p><p><strong>تم الإنشاء بواسطة:</strong> {created_by_name}</p><p><strong>الوصف:</strong> {expense_description}</p><p>مع أطيب التحيات,<br><strong>فريق {app_name}</strong></p>'
                    ],
                    'da' => [
                        'subject' => 'Ny udgift oprettet: {expense_title}',
                        'content' => '<h2>Ny udgift oprettet</h2><p>Hej,</p><p>En ny udgift er oprettet i projektet <strong>{project_name}</strong>.</p><p><strong>Udgift:</strong> {expense_title}</p><p><strong>Beløb:</strong> {expense_amount}</p><p><strong>Kategori:</strong> {expense_category}</p><p><strong>Dato:</strong> {expense_date}</p><p><strong>Oprettet af:</strong> {created_by_name}</p><p><strong>Beskrivelse:</strong> {expense_description}</p><p>Venlig hilsen,<br><strong>{app_name} Teamet</strong></p>'
                    ],
                    'de' => [
                        'subject' => 'Neue Ausgabe erstellt: {expense_title}',
                        'content' => '<h2>Neue Ausgabe erstellt</h2><p>Hallo,</p><p>Es wurde eine neue Ausgabe im Projekt <strong>{project_name}</strong> erstellt.</p><p><strong>Ausgabe:</strong> {expense_title}</p><p><strong>Betrag:</strong> {expense_amount}</p><p><strong>Kategorie:</strong> {expense_category}</p><p><strong>Datum:</strong> {expense_date}</p><p><strong>Erstellt von:</strong> {created_by_name}</p><p><strong>Beschreibung:</strong> {expense_description}</p><p>Mit freundlichen Grüßen,<br><strong>Das {app_name} Team</strong></p>'
                    ],
                    'fr' => [
                        'subject' => 'Nouvelle dépense créée : {expense_title}',
                        'content' => '<h2>Nouvelle dépense créée</h2><p>Bonjour,</p><p>Une nouvelle dépense a été créée dans le projet <strong>{project_name}</strong>.</p><p><strong>Dépense :</strong> {expense_title}</p><p><strong>Montant :</strong> {expense_amount}</p><p><strong>Catégorie :</strong> {expense_category}</p><p><strong>Date :</strong> {expense_date}</p><p><strong>Créé par :</strong> {created_by_name}</p><p><strong>Description :</strong> {expense_description}</p><p>Cordialement,<br><strong>L\'équipe {app_name}</strong></p>'
                    ],
                    'he' => [
                        'subject' => 'הוצאה חדשה נוצרה: {expense_title}',
                        'content' => '<h2>הוצאה חדשה נוצרה</h2><p>שלום,</p><p>נוצרה הוצאה חדשה בפרויקט <strong>{project_name}</strong>.</p><p><strong>הוצאה:</strong> {expense_title}</p><p><strong>סכום:</strong> {expense_amount}</p><p><strong>קטגוריה:</strong> {expense_category}</p><p><strong>תאריך:</strong> {expense_date}</p><p><strong>נוצר על ידי:</strong> {created_by_name}</p><p><strong>תיאור:</strong> {expense_description}</p><p>בברכה,<br><strong>צוות {app_name}</strong></p>'
                    ],
                    'it' => [
                        'subject' => 'Nuova spesa creata: {expense_title}',
                        'content' => '<h2>Nuova spesa creata</h2><p>Ciao,</p><p>È stata creata una nuova spesa nel progetto <strong>{project_name}</strong>.</p><p><strong>Spesa:</strong> {expense_title}</p><p><strong>Importo:</strong> {expense_amount}</p><p><strong>Categoria:</strong> {expense_category}</p><p><strong>Data:</strong> {expense_date}</p><p><strong>Creato da:</strong> {created_by_name}</p><p><strong>Descrizione:</strong> {expense_description}</p><p>Cordiali saluti,<br><strong>Il team {app_name}</strong></p>'
                    ],
                    'ja' => [
                        'subject' => '新しい経費が作成されました: {expense_title}',
                        'content' => '<h2>新しい経費が作成されました</h2><p>こんにちは、</p><p>プロジェクト <strong>{project_name}</strong> に新しい経費が作成されました。</p><p><strong>経費:</strong> {expense_title}</p><p><strong>金額:</strong> {expense_amount}</p><p><strong>カテゴリ:</strong> {expense_category}</p><p><strong>日付:</strong> {expense_date}</p><p><strong>作成者:</strong> {created_by_name}</p><p><strong>説明:</strong> {expense_description}</p><p>よろしくお願いします,<br><strong>{app_name} チーム</strong></p>'
                    ],
                    'nl' => [
                        'subject' => 'Nieuwe uitgave aangemaakt: {expense_title}',
                        'content' => '<h2>Nieuwe uitgave aangemaakt</h2><p>Hallo,</p><p>Er is een nieuwe uitgave aangemaakt in project <strong>{project_name}</strong>.</p><p><strong>Uitgave:</strong> {expense_title}</p><p><strong>Bedrag:</strong> {expense_amount}</p><p><strong>Categorie:</strong> {expense_category}</p><p><strong>Datum:</strong> {expense_date}</p><p><strong>Aangemaakt door:</strong> {created_by_name}</p><p><strong>Beschrijving:</strong> {expense_description}</p><p>Met vriendelijke groet,<br><strong>{app_name} Team</strong></p>'
                    ],
                    'pl' => [
                        'subject' => 'Nowy wydatek utworzony: {expense_title}',
                        'content' => '<h2>Nowy wydatek utworzony</h2><p>Witaj,</p><p>Nowy wydatek został utworzony w projekcie <strong>{project_name}</strong>.</p><p><strong>Wydatek:</strong> {expense_title}</p><p><strong>Kwota:</strong> {expense_amount}</p><p><strong>Kategoria:</strong> {expense_category}</p><p><strong>Data:</strong> {expense_date}</p><p><strong>Utworzono przez:</strong> {created_by_name}</p><p><strong>Opis:</strong> {expense_description}</p><p>Pozdrawiamy,<br><strong>Zespół {app_name}</strong></p>'
                    ],
                    'pt' => [
                        'subject' => 'Nova despesa criada: {expense_title}',
                        'content' => '<h2>Nova despesa criada</h2><p>Olá,</p><p>Uma nova despesa foi criada no projeto <strong>{project_name}</strong>.</p><p><strong>Despesa:</strong> {expense_title}</p><p><strong>Valor:</strong> {expense_amount}</p><p><strong>Categoria:</strong> {expense_category}</p><p><strong>Data:</strong> {expense_date}</p><p><strong>Criado por:</strong> {created_by_name}</p><p><strong>Descrição:</strong> {expense_description}</p><p>Atenciosamente,<br><strong>Equipe {app_name}</strong></p>'
                    ],
                    'pt-BR' => [
                        'subject' => 'Nova despesa criada: {expense_title}',
                        'content' => '<h2>Nova despesa criada</h2><p>Olá,</p><p>Uma nova despesa foi criada no projeto <strong>{project_name}</strong>.</p><p><strong>Despesa:</strong> {expense_title}</p><p><strong>Valor:</strong> {expense_amount}</p><p><strong>Categoria:</strong> {expense_category}</p><p><strong>Data:</strong> {expense_date}</p><p><strong>Criado por:</strong> {created_by_name}</p><p><strong>Descrição:</strong> {expense_description}</p><p>Atenciosamente,<br><strong>Equipe {app_name}</strong></p>'
                    ],
                    'ru' => [
                        'subject' => 'Создан новый расход: {expense_title}',
                        'content' => '<h2>Создан новый расход</h2><p>Здравствуйте,</p><p>В проекте <strong>{project_name}</strong> был создан новый расход.</p><p><strong>Расход:</strong> {expense_title}</p><p><strong>Сумма:</strong> {expense_amount}</p><p><strong>Категория:</strong> {expense_category}</p><p><strong>Дата:</strong> {expense_date}</p><p><strong>Создано:</strong> {created_by_name}</p><p><strong>Описание:</strong> {expense_description}</p><p>С уважением,<br><strong>Команда {app_name}</strong></p>'
                    ],
                    'tr' => [
                        'subject' => 'Yeni gider oluşturuldu: {expense_title}',
                        'content' => '<h2>Yeni gider oluşturuldu</h2><p>Merhaba,</p><p><strong>{project_name}</strong> projesinde yeni bir gider oluşturuldu.</p><p><strong>Gider:</strong> {expense_title}</p><p><strong>Tutar:</strong> {expense_amount}</p><p><strong>Kategori:</strong> {expense_category}</p><p><strong>Tarih:</strong> {expense_date}</p><p><strong>Oluşturan:</strong> {created_by_name}</p><p><strong>Açıklama:</strong> {expense_description}</p><p>Saygılarımızla,<br><strong>{app_name} Ekibi</strong></p>'
                    ],
                    'zh' => [
                        'subject' => '已创建新费用: {expense_title}',
                        'content' => '<h2>已创建新费用</h2><p>您好，</p><p>在项目 <strong>{project_name}</strong> 中已创建新费用。</p><p><strong>费用:</strong> {expense_title}</p><p><strong>金额:</strong> {expense_amount}</p><p><strong>类别:</strong> {expense_category}</p><p><strong>日期:</strong> {expense_date}</p><p><strong>创建者:</strong> {created_by_name}</p><p><strong>描述:</strong> {expense_description}</p><p>此致敬礼,<br><strong>{app_name} 团队</strong></p>'
                    ],
                ]
            ],
            [
                'name' => 'Invoice Notification',
                'from' => 'Billing Team',
                'translations' => [
                    'en' => [
                        'subject' => 'New Invoice Created: {invoice_number}',
                        'content' => '<h2>New Invoice Created</h2><p>Hello <strong>{client_name}</strong>,</p><p>A new invoice has been created for you.</p><p><strong>Invoice Number:</strong> {invoice_number}</p><p><strong>Invoice Title:</strong> {invoice_title}</p><p><strong>Project:</strong> {project_name}</p><p><strong>Total Amount:</strong> {total_amount} {currency}</p><p><strong>Due Date:</strong> {due_date}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Created by:</strong> {creator_name}</p><p>Best regards,<br><strong>The {app_name} Team</strong></p>'
                    ],
                    'es' => [
                        'subject' => 'Nueva factura creada: {invoice_number}',
                        'content' => '<h2>Nueva factura creada</h2><p>Hola <strong>{client_name}</strong>,</p><p>Se ha creado una nueva factura para ti.</p><p><strong>Número de factura:</strong> {invoice_number}</p><p><strong>Título de la factura:</strong> {invoice_title}</p><p><strong>Proyecto:</strong> {project_name}</p><p><strong>Importe total:</strong> {total_amount} {currency}</p><p><strong>Fecha de vencimiento:</strong> {due_date}</p><p><strong>Espacio de trabajo:</strong> {workspace_name}</p><p><strong>Creado por:</strong> {creator_name}</p><p>Saludos cordiales,<br><strong>El equipo de {app_name}</strong></p>'
                    ],
                    'ar' => [
                        'subject' => 'تم إنشاء فاتورة جديدة: {invoice_number}',
                        'content' => '<h2>تم إنشاء فاتورة جديدة</h2><p>مرحباً <strong>{client_name}</strong>،</p><p>تم إنشاء فاتورة جديدة لك.</p><p><strong>رقم الفاتورة:</strong> {invoice_number}</p><p><strong>عنوان الفاتورة:</strong> {invoice_title}</p><p><strong>المشروع:</strong> {project_name}</p><p><strong>المبلغ الإجمالي:</strong> {total_amount} {currency}</p><p><strong>تاريخ الاستحقاق:</strong> {due_date}</p><p><strong>مساحة العمل:</strong> {workspace_name}</p><p><strong>تم الإنشاء بواسطة:</strong> {creator_name}</p><p>مع أطيب التحيات,<br><strong>فريق {app_name}</strong></p>'
                    ],
                    'da' => [
                        'subject' => 'Ny faktura oprettet: {invoice_number}',
                        'content' => '<h2>Ny faktura oprettet</h2><p>Hej <strong>{client_name}</strong>,</p><p>Der er oprettet en ny faktura til dig.</p><p><strong>Fakturanummer:</strong> {invoice_number}</p><p><strong>Fakturatitel:</strong> {invoice_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Samlet beløb:</strong> {total_amount} {currency}</p><p><strong>Forfaldsdato:</strong> {due_date}</p><p><strong>Arbejdsområde:</strong> {workspace_name}</p><p><strong>Oprettet af:</strong> {creator_name}</p><p>Venlig hilsen,<br><strong>{app_name} Teamet</strong></p>'
                    ],
                    'de' => [
                        'subject' => 'Neue Rechnung erstellt: {invoice_number}',
                        'content' => '<h2>Neue Rechnung erstellt</h2><p>Hallo <strong>{client_name}</strong>,</p><p>Es wurde eine neue Rechnung für Sie erstellt.</p><p><strong>Rechnungsnummer:</strong> {invoice_number}</p><p><strong>Rechnungstitel:</strong> {invoice_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Gesamtbetrag:</strong> {total_amount} {currency}</p><p><strong>Fälligkeitsdatum:</strong> {due_date}</p><p><strong>Arbeitsbereich:</strong> {workspace_name}</p><p><strong>Erstellt von:</strong> {creator_name}</p><p>Mit freundlichen Grüßen,<br><strong>Das {app_name} Team</strong></p>'
                    ],
                    'fr' => [
                        'subject' => 'Nouvelle facture créée : {invoice_number}',
                        'content' => '<h2>Nouvelle facture créée</h2><p>Bonjour <strong>{client_name}</strong>,</p><p>Une nouvelle facture a été créée pour vous.</p><p><strong>Numéro de facture :</strong> {invoice_number}</p><p><strong>Titre de la facture :</strong> {invoice_title}</p><p><strong>Projet :</strong> {project_name}</p><p><strong>Montant total :</strong> {total_amount} {currency}</p><p><strong>Date d\'échéance :</strong> {due_date}</p><p><strong>Espace de travail :</strong> {workspace_name}</p><p><strong>Créé par :</strong> {creator_name}</p><p>Cordialement,<br><strong>L\'équipe {app_name}</strong></p>'
                    ],
                    'he' => [
                        'subject' => 'חשבונית חדשה נוצרה: {invoice_number}',
                        'content' => '<h2>חשבונית חדשה נוצרה</h2><p>שלום <strong>{client_name}</strong>,</p><p>נוצרה עבורך חשבונית חדשה.</p><p><strong>מספר חשבונית:</strong> {invoice_number}</p><p><strong>כותרת החשבונית:</strong> {invoice_title}</p><p><strong>פרויקט:</strong> {project_name}</p><p><strong>סכום כולל:</strong> {total_amount} {currency}</p><p><strong>תאריך אחרון לתשלום:</strong> {due_date}</p><p><strong>מרחב עבודה:</strong> {workspace_name}</p><p><strong>נוצר על ידי:</strong> {creator_name}</p><p>בברכה,<br><strong>צוות {app_name}</strong></p>'
                    ],
                    'it' => [
                        'subject' => 'Nuova fattura creata: {invoice_number}',
                        'content' => '<h2>Nuova fattura creata</h2><p>Ciao <strong>{client_name}</strong>,</p><p>È stata creata una nuova fattura per te.</p><p><strong>Numero fattura:</strong> {invoice_number}</p><p><strong>Titolo fattura:</strong> {invoice_title}</p><p><strong>Progetto:</strong> {project_name}</p><p><strong>Importo totale:</strong> {total_amount} {currency}</p><p><strong>Data di scadenza:</strong> {due_date}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Creato da:</strong> {creator_name}</p><p>Cordiali saluti,<br><strong>Team {app_name}</strong></p>'
                    ],
                    'ja' => [
                        'subject' => '新しい請求書が作成されました: {invoice_number}',
                        'content' => '<h2>新しい請求書が作成されました</h2><p>こんにちは <strong>{client_name}</strong> さん、</p><p>新しい請求書が作成されました。</p><p><strong>請求書番号:</strong> {invoice_number}</p><p><strong>請求書タイトル:</strong> {invoice_title}</p><p><strong>プロジェクト:</strong> {project_name}</p><p><strong>合計金額:</strong> {total_amount} {currency}</p><p><strong>支払期限:</strong> {due_date}</p><p><strong>ワークスペース:</strong> {workspace_name}</p><p><strong>作成者:</strong> {creator_name}</p><p>よろしくお願いします,<br><strong>{app_name} チーム</strong></p>'
                    ],
                    'nl' => [
                        'subject' => 'Nieuwe factuur aangemaakt: {invoice_number}',
                        'content' => '<h2>Nieuwe factuur aangemaakt</h2><p>Hallo <strong>{client_name}</strong>,</p><p>Er is een nieuwe factuur voor je aangemaakt.</p><p><strong>Factuurnummer:</strong> {invoice_number}</p><p><strong>Factuurtitel:</strong> {invoice_title}</p><p><strong>Project:</strong> {project_name}</p><p><strong>Totaalbedrag:</strong> {total_amount} {currency}</p><p><strong>Vervaldatum:</strong> {due_date}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Gemaakt door:</strong> {creator_name}</p><p>Met vriendelijke groet,<br><strong>{app_name} Team</strong></p>'
                    ],
                    'pl' => [
                        'subject' => 'Nowa faktura utworzona: {invoice_number}',
                        'content' => '<h2>Nowa faktura utworzona</h2><p>Witaj <strong>{client_name}</strong>,</p><p>Utworzono dla Ciebie nową fakturę.</p><p><strong>Numer faktury:</strong> {invoice_number}</p><p><strong>Tytuł faktury:</strong> {invoice_title}</p><p><strong>Projekt:</strong> {project_name}</p><p><strong>Kwota całkowita:</strong> {total_amount} {currency}</p><p><strong>Data płatności:</strong> {due_date}</p><p><strong>Workspace:</strong> {workspace_name}</p><p><strong>Utworzono przez:</strong> {creator_name}</p><p>Pozdrawiamy,<br><strong>Zespół {app_name}</strong></p>'
                    ],
                    'pt' => [
                        'subject' => 'Nova fatura criada: {invoice_number}',
                        'content' => '<h2>Nova fatura criada</h2><p>Olá <strong>{client_name}</strong>,</p><p>Uma nova fatura foi criada para você.</p><p><strong>Número da fatura:</strong> {invoice_number}</p><p><strong>Título da fatura:</strong> {invoice_title}</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Valor total:</strong> {total_amount} {currency}</p><p><strong>Data de vencimento:</strong> {due_date}</p><p><strong>Espaço de trabalho:</strong> {workspace_name}</p><p><strong>Criado por:</strong> {creator_name}</p><p>Atenciosamente,<br><strong>Equipe {app_name}</strong></p>'
                    ],
                    'pt-BR' => [
                        'subject' => 'Nova fatura criada: {invoice_number}',
                        'content' => '<h2>Nova fatura criada</h2><p>Olá <strong>{client_name}</strong>,</p><p>Uma nova fatura foi criada para você.</p><p><strong>Número da fatura:</strong> {invoice_number}</p><p><strong>Título da fatura:</strong> {invoice_title}</p><p><strong>Projeto:</strong> {project_name}</p><p><strong>Valor total:</strong> {total_amount} {currency}</p><p><strong>Data de vencimento:</strong> {due_date}</p><p><strong>Espaço de trabalho:</strong> {workspace_name}</p><p><strong>Criado por:</strong> {creator_name}</p><p>Atenciosamente,<br><strong>Equipe {app_name}</strong></p>'
                    ],
                    'ru' => [
                        'subject' => 'Создан новый счет: {invoice_number}',
                        'content' => '<h2>Создан новый счет</h2><p>Здравствуйте <strong>{client_name}</strong>,</p><p>Для вас создан новый счет.</p><p><strong>Номер счета:</strong> {invoice_number}</p><p><strong>Название счета:</strong> {invoice_title}</p><p><strong>Проект:</strong> {project_name}</p><p><strong>Общая сумма:</strong> {total_amount} {currency}</p><p><strong>Срок оплаты:</strong> {due_date}</p><p><strong>Рабочее пространство:</strong> {workspace_name}</p><p><strong>Создано:</strong> {creator_name}</p><p>С уважением,<br><strong>Команда {app_name}</strong></p>'
                    ],
                    'tr' => [
                        'subject' => 'Yeni fatura oluşturuldu: {invoice_number}',
                        'content' => '<h2>Yeni fatura oluşturuldu</h2><p>Merhaba <strong>{client_name}</strong>,</p><p>Sizin için yeni bir fatura oluşturuldu.</p><p><strong>Fatura Numarası:</strong> {invoice_number}</p><p><strong>Fatura Başlığı:</strong> {invoice_title}</p><p><strong>Proje:</strong> {project_name}</p><p><strong>Toplam Tutar:</strong> {total_amount} {currency}</p><p><strong>Son Tarih:</strong> {due_date}</p><p><strong>Çalışma Alanı:</strong> {workspace_name}</p><p><strong>Oluşturan:</strong> {creator_name}</p><p>Saygılarımızla,<br><strong>{app_name} Ekibi</strong></p>'
                    ],
                    'zh' => [
                        'subject' => '已创建新发票: {invoice_number}',
                        'content' => '<h2>已创建新发票</h2><p>您好 <strong>{client_name}</strong>,</p><p>已为您创建新发票。</p><p><strong>发票号码:</strong> {invoice_number}</p><p><strong>发票标题:</strong> {invoice_title}</p><p><strong>项目:</strong> {project_name}</p><p><strong>总金额:</strong> {total_amount} {currency}</p><p><strong>到期日期:</strong> {due_date}</p><p><strong>工作区:</strong> {workspace_name}</p><p><strong>创建者:</strong> {creator_name}</p><p>此致敬礼,<br><strong>{app_name} 团队</strong></p>'
                    ],
                ]
            ]
        ];

        foreach ($templates as $templateData) {
            $template = EmailTemplate::updateOrCreate([
                'name' => $templateData['name'],
                'from' => $templateData['from'],
                'user_id' => auth()->id() ?? 1
            ]);

            foreach ($supportedLanguages as $langCode) {
                $translation = $templateData['translations'][$langCode] ?? $templateData['translations']['en'];

                EmailTemplateLang::updateOrCreate([
                    'parent_id' => $template->id,
                    'lang' => $langCode,
                    'subject' => $translation['subject'],
                    'content' => $translation['content']
                ]);
            }

            UserEmailTemplate::updateOrCreate([
                'template_id' => $template->id,
                'user_id' => auth()->id() ?? 1,
                'is_active' => false
            ]);
        }
    }
}