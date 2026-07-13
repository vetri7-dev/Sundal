<?php

namespace Database\Seeders;

use App\Models\NotificationTemplate;
use App\Models\NotificationTemplateLang;
use Illuminate\Database\Seeder;

class NotificationTemplateSeeder extends Seeder
{
    public function run(): void
    {
        $supportedLanguages = ['en', 'es', 'ar', 'da', 'de', 'fr', 'he', 'it', 'ja', 'nl', 'pl', 'pt', 'pt-BR', 'ru', 'tr', 'zh'];

        $templates = [
            [
                'name' => 'New Project',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'New Project Created: {project_name}',
                        'content' => 'A new project "{project_name}" has been created by {created_by}. Start date: {start_date}, End date: {end_date}.'
                    ],
                    'es' => [
                        'title' => 'Nuevo proyecto creado: {project_name}',
                        'content' => 'Se ha creado un nuevo proyecto "{project_name}" por {created_by}. Fecha de inicio: {start_date}, Fecha de finalización: {end_date}.'
                    ],
                    'ar' => [
                        'title' => 'تم إنشاء مشروع جديد: {project_name}',
                        'content' => 'تم إنشاء مشروع جديد "{project_name}" بواسطة {created_by}. تاريخ البدء: {start_date}، تاريخ الانتهاء: {end_date}.'
                    ],
                    'da' => [
                        'title' => 'Nyt projekt oprettet: {project_name}',
                        'content' => 'Et nyt projekt "{project_name}" er blevet oprettet af {created_by}. Startdato: {start_date}, Slutdato: {end_date}.'
                    ],
                    'de' => [
                        'title' => 'Neues Projekt erstellt: {project_name}',
                        'content' => 'Ein neues Projekt "{project_name}" wurde von {created_by} erstellt. Startdatum: {start_date}, Enddatum: {end_date}.'
                    ],
                    'fr' => [
                        'title' => 'Nouveau projet créé : {project_name}',
                        'content' => 'Un nouveau projet "{project_name}" a été créé par {created_by}. Date de début : {start_date}, Date de fin : {end_date}.'
                    ],
                    'he' => [
                        'title' => 'נוצר פרויקט חדש: {project_name}',
                        'content' => 'פרויקט חדש "{project_name}" נוצר על ידי {created_by}. תאריך התחלה: {start_date}, תאריך סיום: {end_date}.'
                    ],
                    'it' => [
                        'title' => 'Nuovo progetto creato: {project_name}',
                        'content' => 'È stato creato un nuovo progetto "{project_name}" da {created_by}. Data di inizio: {start_date}, Data di fine: {end_date}.'
                    ],
                    'ja' => [
                        'title' => '新しいプロジェクトが作成されました: {project_name}',
                        'content' => '新しいプロジェクト「{project_name}」が {created_by} によって作成されました。開始日: {start_date}、終了日: {end_date}。'
                    ],
                    'nl' => [
                        'title' => 'Nieuw project aangemaakt: {project_name}',
                        'content' => 'Een nieuw project "{project_name}" is aangemaakt door {created_by}. Startdatum: {start_date}, Einddatum: {end_date}.'
                    ],
                    'pl' => [
                        'title' => 'Nowy projekt utworzony: {project_name}',
                        'content' => 'Nowy projekt "{project_name}" został utworzony przez {created_by}. Data rozpoczęcia: {start_date}, Data zakończenia: {end_date}.'
                    ],
                    'pt' => [
                        'title' => 'Novo projeto criado: {project_name}',
                        'content' => 'Um novo projeto "{project_name}" foi criado por {created_by}. Data de início: {start_date}, Data de término: {end_date}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Novo projeto criado: {project_name}',
                        'content' => 'Um novo projeto "{project_name}" foi criado por {created_by}. Data de início: {start_date}, Data de término: {end_date}.'
                    ],
                    'ru' => [
                        'title' => 'Создан новый проект: {project_name}',
                        'content' => 'Новый проект "{project_name}" был создан пользователем {created_by}. Дата начала: {start_date}, Дата окончания: {end_date}.'
                    ],
                    'tr' => [
                        'title' => 'Yeni Proje Oluşturuldu: {project_name}',
                        'content' => 'Yeni bir proje "{project_name}", {created_by} tarafından oluşturuldu. Başlangıç tarihi: {start_date}, Bitiş tarihi: {end_date}.'
                    ],
                    'zh' => [
                        'title' => '新项目已创建: {project_name}',
                        'content' => '一个新项目“{project_name}”已由 {created_by} 创建。开始日期: {start_date}，结束日期: {end_date}。'
                    ],
                ]
            ],
            [
                'name' => 'New Task',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'New Task Assigned: {task_title}',
                        'content' => 'You have been assigned a new task "{task_title}" in project {project_name}. Due date: {due_date}.'
                    ],
                    'es' => [
                        'title' => 'Nueva tarea asignada: {task_title}',
                        'content' => 'Se le ha asignado una nueva tarea "{task_title}" en el proyecto {project_name}. Fecha de vencimiento: {due_date}.'
                    ],
                    'ar' => [
                        'title' => 'تم تعيين مهمة جديدة: {task_title}',
                        'content' => 'تم تعيين مهمة جديدة "{task_title}" لك في المشروع {project_name}. تاريخ الاستحقاق: {due_date}.'
                    ],
                    'da' => [
                        'title' => 'Ny opgave tildelt: {task_title}',
                        'content' => 'Du er blevet tildelt en ny opgave "{task_title}" i projektet {project_name}. Forfaldsdato: {due_date}.'
                    ],
                    'de' => [
                        'title' => 'Neue Aufgabe zugewiesen: {task_title}',
                        'content' => 'Ihnen wurde eine neue Aufgabe "{task_title}" im Projekt {project_name} zugewiesen. Fälligkeitsdatum: {due_date}.'
                    ],
                    'fr' => [
                        'title' => 'Nouvelle tâche attribuée : {task_title}',
                        'content' => 'Une nouvelle tâche "{task_title}" vous a été attribuée dans le projet {project_name}. Date d\'échéance : {due_date}.'
                    ],
                    'he' => [
                        'title' => 'משימה חדשה הוקצתה: {task_title}',
                        'content' => 'הוקצתה לך משימה חדשה "{task_title}" בפרויקט {project_name}. תאריך יעד: {due_date}.'
                    ],
                    'it' => [
                        'title' => 'Nuovo compito assegnato: {task_title}',
                        'content' => 'Ti è stato assegnato un nuovo compito "{task_title}" nel progetto {project_name}. Data di scadenza: {due_date}.'
                    ],
                    'ja' => [
                        'title' => '新しいタスクが割り当てられました: {task_title}',
                        'content' => 'あなたに新しいタスク「{task_title}」がプロジェクト {project_name} で割り当てられました。期限: {due_date}。'
                    ],
                    'nl' => [
                        'title' => 'Nieuwe taak toegewezen: {task_title}',
                        'content' => 'U bent toegewezen aan een nieuwe taak "{task_title}" in project {project_name}. Vervaldatum: {due_date}.'
                    ],
                    'pl' => [
                        'title' => 'Nowe zadanie przydzielone: {task_title}',
                        'content' => 'Przydzielono Ci nowe zadanie "{task_title}" w projekcie {project_name}. Termin: {due_date}.'
                    ],
                    'pt' => [
                        'title' => 'Nova tarefa atribuída: {task_title}',
                        'content' => 'Foi-lhe atribuída uma nova tarefa "{task_title}" no projeto {project_name}. Data de vencimento: {due_date}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Nova tarefa atribuída: {task_title}',
                        'content' => 'Foi atribuída a você uma nova tarefa "{task_title}" no projeto {project_name}. Data de vencimento: {due_date}.'
                    ],
                    'ru' => [
                        'title' => 'Назначена новая задача: {task_title}',
                        'content' => 'Вам была назначена новая задача "{task_title}" в проекте {project_name}. Срок выполнения: {due_date}.'
                    ],
                    'tr' => [
                        'title' => 'Yeni Görev Atandı: {task_title}',
                        'content' => 'Size {project_name} projesinde yeni bir görev "{task_title}" atandı. Son teslim tarihi: {due_date}.'
                    ],
                    'zh' => [
                        'title' => '新任务已分配: {task_title}',
                        'content' => '您已在项目 {project_name} 中被分配新任务“{task_title}”。截止日期: {due_date}。'
                    ],
                ]
            ],
            [
                'name' => 'Task Stage Updated',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'Task Stage Updated: {task_title}',
                        'content' => 'Task "{task_title}" stage has been updated from {old_stage} to {new_stage} by {updated_by}.'
                    ],
                    'es' => [
                        'title' => 'Etapa de tarea actualizada: {task_title}',
                        'content' => 'La etapa de la tarea "{task_title}" ha sido actualizada de {old_stage} a {new_stage} por {updated_by}.'
                    ],
                    'ar' => [
                        'title' => 'تم تحديث مرحلة المهمة: {task_title}',
                        'content' => 'تم تحديث مرحلة المهمة "{task_title}" من {old_stage} إلى {new_stage} بواسطة {updated_by}.'
                    ],
                    'da' => [
                        'title' => 'Opgavestatus opdateret: {task_title}',
                        'content' => 'Opgaven "{task_title}" er blevet opdateret fra {old_stage} til {new_stage} af {updated_by}.'
                    ],
                    'de' => [
                        'title' => 'Aufgabenstatus aktualisiert: {task_title}',
                        'content' => 'Die Aufgabe "{task_title}" wurde von {updated_by} von {old_stage} auf {new_stage} aktualisiert.'
                    ],
                    'fr' => [
                        'title' => 'Étape de tâche mise à jour : {task_title}',
                        'content' => 'La tâche "{task_title}" a été mise à jour de {old_stage} à {new_stage} par {updated_by}.'
                    ],
                    'he' => [
                        'title' => 'שלב המשימה עודכן: {task_title}',
                        'content' => 'שלב המשימה "{task_title}" עודכן מ-{old_stage} ל-{new_stage} על ידי {updated_by}.'
                    ],
                    'it' => [
                        'title' => 'Fase attività aggiornata: {task_title}',
                        'content' => 'La fase dell\'attività "{task_title}" è stata aggiornata da {old_stage} a {new_stage} da {updated_by}.'
                    ],
                    'ja' => [
                        'title' => 'タスクステージが更新されました: {task_title}',
                        'content' => 'タスク「{task_title}」のステージが {updated_by} によって {old_stage} から {new_stage} に更新されました。'
                    ],
                    'nl' => [
                        'title' => 'Taakfase bijgewerkt: {task_title}',
                        'content' => 'De taak "{task_title}" is bijgewerkt van {old_stage} naar {new_stage} door {updated_by}.'
                    ],
                    'pl' => [
                        'title' => 'Zaktualizowano etap zadania: {task_title}',
                        'content' => 'Zadanie "{task_title}" zostało zaktualizowane z {old_stage} do {new_stage} przez {updated_by}.'
                    ],
                    'pt' => [
                        'title' => 'Etapa da tarefa atualizada: {task_title}',
                        'content' => 'A etapa da tarefa "{task_title}" foi atualizada de {old_stage} para {new_stage} por {updated_by}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Etapa da tarefa atualizada: {task_title}',
                        'content' => 'A etapa da tarefa "{task_title}" foi atualizada de {old_stage} para {new_stage} por {updated_by}.'
                    ],
                    'ru' => [
                        'title' => 'Этап задачи обновлен: {task_title}',
                        'content' => 'Этап задачи "{task_title}" был обновлен с {old_stage} на {new_stage} пользователем {updated_by}.'
                    ],
                    'tr' => [
                        'title' => 'Görev Aşaması Güncellendi: {task_title}',
                        'content' => '"{task_title}" görevinin aşaması {updated_by} tarafından {old_stage} aşamasından {new_stage} aşamasına güncellendi.'
                    ],
                    'zh' => [
                        'title' => '任务阶段已更新: {task_title}',
                        'content' => '任务“{task_title}”的阶段已由 {updated_by} 从 {old_stage} 更新为 {new_stage}。'
                    ],
                ]
            ],
            [
                'name' => 'New Milestone',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'New Milestone Created: {milestone_title}',
                        'content' => 'A new milestone "{milestone_title}" has been created in project {project_name}. Due date: {due_date}.'
                    ],
                    'es' => [
                        'title' => 'Nuevo hito creado: {milestone_title}',
                        'content' => 'Se ha creado un nuevo hito "{milestone_title}" en el proyecto {project_name}. Fecha de vencimiento: {due_date}.'
                    ],
                    'ar' => [
                        'title' => 'تم إنشاء معلم جديد: {milestone_title}',
                        'content' => 'تم إنشاء معلم جديد "{milestone_title}" في المشروع {project_name}. تاريخ الاستحقاق: {due_date}.'
                    ],
                    'da' => [
                        'title' => 'Ny milepæl oprettet: {milestone_title}',
                        'content' => 'En ny milepæl "{milestone_title}" er blevet oprettet i projektet {project_name}. Forfaldsdato: {due_date}.'
                    ],
                    'de' => [
                        'title' => 'Neuer Meilenstein erstellt: {milestone_title}',
                        'content' => 'Ein neuer Meilenstein "{milestone_title}" wurde im Projekt {project_name} erstellt. Fälligkeitsdatum: {due_date}.'
                    ],
                    'fr' => [
                        'title' => 'Nouveau jalon créé : {milestone_title}',
                        'content' => 'Un nouveau jalon "{milestone_title}" a été créé dans le projet {project_name}. Date d\'échéance : {due_date}.'
                    ],
                    'he' => [
                        'title' => 'אבני דרך חדשים נוצרו: {milestone_title}',
                        'content' => 'נוצר אבן דרך חדש "{milestone_title}" בפרויקט {project_name}. תאריך יעד: {due_date}.'
                    ],
                    'it' => [
                        'title' => 'Nuova pietra miliare creata: {milestone_title}',
                        'content' => 'È stata creata una nuova pietra miliare "{milestone_title}" nel progetto {project_name}. Data di scadenza: {due_date}.'
                    ],
                    'ja' => [
                        'title' => '新しいマイルストーンが作成されました: {milestone_title}',
                        'content' => 'プロジェクト {project_name} に新しいマイルストーン「{milestone_title}」が作成されました。期限: {due_date}。'
                    ],
                    'nl' => [
                        'title' => 'Nieuwe mijlpaal gemaakt: {milestone_title}',
                        'content' => 'Een nieuwe mijlpaal "{milestone_title}" is aangemaakt in project {project_name}. Vervaldatum: {due_date}.'
                    ],
                    'pl' => [
                        'title' => 'Nowy kamień milowy utworzony: {milestone_title}',
                        'content' => 'Nowy kamień milowy "{milestone_title}" został utworzony w projekcie {project_name}. Termin: {due_date}.'
                    ],
                    'pt' => [
                        'title' => 'Novo marco criado: {milestone_title}',
                        'content' => 'Um novo marco "{milestone_title}" foi criado no projeto {project_name}. Data de vencimento: {due_date}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Novo marco criado: {milestone_title}',
                        'content' => 'Um novo marco "{milestone_title}" foi criado no projeto {project_name}. Data de vencimento: {due_date}.'
                    ],
                    'ru' => [
                        'title' => 'Создан новый этап: {milestone_title}',
                        'content' => 'Новый этап "{milestone_title}" был создан в проекте {project_name}. Срок выполнения: {due_date}.'
                    ],
                    'tr' => [
                        'title' => 'Yeni Kilometre Taşı Oluşturuldu: {milestone_title}',
                        'content' => 'Yeni bir kilometre taşı "{milestone_title}", {project_name} projesinde oluşturuldu. Son tarih: {due_date}.'
                    ],
                    'zh' => [
                        'title' => '新里程碑已创建: {milestone_title}',
                        'content' => '项目 {project_name} 中已创建新的里程碑“{milestone_title}”。截止日期: {due_date}。'
                    ],
                ]
            ],
            [
                'name' => 'Milestone Status Updated',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'Milestone Status Updated: {milestone_title}',
                        'content' => 'Milestone "{milestone_title}" status has been updated to {status} by {updated_by}.'
                    ],
                    'es' => [
                        'title' => 'Estado del hito actualizado: {milestone_title}',
                        'content' => 'El estado del hito "{milestone_title}" ha sido actualizado a {status} por {updated_by}.'
                    ],
                    'ar' => [
                        'title' => 'تم تحديث حالة المعلم: {milestone_title}',
                        'content' => 'تم تحديث حالة المعلم "{milestone_title}" إلى {status} بواسطة {updated_by}.'
                    ],
                    'da' => [
                        'title' => 'Milepælsstatus opdateret: {milestone_title}',
                        'content' => 'Milepæl "{milestone_title}" status er blevet opdateret til {status} af {updated_by}.'
                    ],
                    'de' => [
                        'title' => 'Meilensteinstatus aktualisiert: {milestone_title}',
                        'content' => 'Der Status des Meilensteins "{milestone_title}" wurde von {updated_by} auf {status} aktualisiert.'
                    ],
                    'fr' => [
                        'title' => 'Statut du jalon mis à jour : {milestone_title}',
                        'content' => 'Le statut du jalon "{milestone_title}" a été mis à jour en {status} par {updated_by}.'
                    ],
                    'he' => [
                        'title' => 'סטטוס אבן הדרך עודכן: {milestone_title}',
                        'content' => 'סטטוס אבן הדרך "{milestone_title}" עודכן ל-{status} על ידי {updated_by}.'
                    ],
                    'it' => [
                        'title' => 'Stato della pietra miliare aggiornato: {milestone_title}',
                        'content' => 'Lo stato della pietra miliare "{milestone_title}" è stato aggiornato a {status} da {updated_by}.'
                    ],
                    'ja' => [
                        'title' => 'マイルストーンのステータスが更新されました: {milestone_title}',
                        'content' => 'マイルストーン「{milestone_title}」のステータスが {updated_by} によって {status} に更新されました。'
                    ],
                    'nl' => [
                        'title' => 'Mijlpaalstatus bijgewerkt: {milestone_title}',
                        'content' => 'De status van de mijlpaal "{milestone_title}" is bijgewerkt naar {status} door {updated_by}.'
                    ],
                    'pl' => [
                        'title' => 'Zaktualizowano status kamienia milowego: {milestone_title}',
                        'content' => 'Status kamienia milowego "{milestone_title}" został zaktualizowany na {status} przez {updated_by}.'
                    ],
                    'pt' => [
                        'title' => 'Status do marco atualizado: {milestone_title}',
                        'content' => 'O status do marco "{milestone_title}" foi atualizado para {status} por {updated_by}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Status do marco atualizado: {milestone_title}',
                        'content' => 'O status do marco "{milestone_title}" foi atualizado para {status} por {updated_by}.'
                    ],
                    'ru' => [
                        'title' => 'Статус этапа обновлен: {milestone_title}',
                        'content' => 'Статус этапа "{milestone_title}" был обновлен на {status} пользователем {updated_by}.'
                    ],
                    'tr' => [
                        'title' => 'Kilometre Taşı Durumu Güncellendi: {milestone_title}',
                        'content' => '"{milestone_title}" kilometre taşının durumu {updated_by} tarafından {status} olarak güncellendi.'
                    ],
                    'zh' => [
                        'title' => '里程碑状态已更新: {milestone_title}',
                        'content' => '里程碑“{milestone_title}”的状态已由 {updated_by} 更新为 {status}。'
                    ],
                ]
            ],
            [
                'name' => 'New Task Comment',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'New Comment on Task: {task_title}',
                        'content' => '{commenter_name} has added a new comment on task "{task_title}": {comment_text}'
                    ],
                    'es' => [
                        'title' => 'Nuevo comentario en la tarea: {task_title}',
                        'content' => '{commenter_name} ha agregado un nuevo comentario en la tarea "{task_title}": {comment_text}'
                    ],
                    'ar' => [
                        'title' => 'تعليق جديد على المهمة: {task_title}',
                        'content' => '{commenter_name} أضاف تعليقًا جديدًا على المهمة "{task_title}": {comment_text}'
                    ],
                    'da' => [
                        'title' => 'Ny kommentar til opgave: {task_title}',
                        'content' => '{commenter_name} har tilføjet en ny kommentar til opgaven "{task_title}": {comment_text}'
                    ],
                    'de' => [
                        'title' => 'Neuer Kommentar zur Aufgabe: {task_title}',
                        'content' => '{commenter_name} hat einen neuen Kommentar zur Aufgabe "{task_title}" hinzugefügt: {comment_text}'
                    ],
                    'fr' => [
                        'title' => 'Nouveau commentaire sur la tâche : {task_title}',
                        'content' => '{commenter_name} a ajouté un nouveau commentaire sur la tâche "{task_title}" : {comment_text}'
                    ],
                    'he' => [
                        'title' => 'תגובה חדשה על המשימה: {task_title}',
                        'content' => '{commenter_name} הוסיף תגובה חדשה למשימה "{task_title}": {comment_text}'
                    ],
                    'it' => [
                        'title' => 'Nuovo commento sul compito: {task_title}',
                        'content' => '{commenter_name} ha aggiunto un nuovo commento al compito "{task_title}": {comment_text}'
                    ],
                    'ja' => [
                        'title' => 'タスクへの新しいコメント: {task_title}',
                        'content' => '{commenter_name} がタスク「{task_title}」に新しいコメントを追加しました: {comment_text}'
                    ],
                    'nl' => [
                        'title' => 'Nieuwe reactie op taak: {task_title}',
                        'content' => '{commenter_name} heeft een nieuwe reactie toegevoegd op taak "{task_title}": {comment_text}'
                    ],
                    'pl' => [
                        'title' => 'Nowy komentarz do zadania: {task_title}',
                        'content' => '{commenter_name} dodał nowy komentarz do zadania "{task_title}": {comment_text}'
                    ],
                    'pt' => [
                        'title' => 'Novo comentário na tarefa: {task_title}',
                        'content' => '{commenter_name} adicionou um novo comentário na tarefa "{task_title}": {comment_text}'
                    ],
                    'pt-BR' => [
                        'title' => 'Novo comentário na tarefa: {task_title}',
                        'content' => '{commenter_name} adicionou um novo comentário na tarefa "{task_title}": {comment_text}'
                    ],
                    'ru' => [
                        'title' => 'Новый комментарий к задаче: {task_title}',
                        'content' => '{commenter_name} добавил новый комментарий к задаче "{task_title}": {comment_text}'
                    ],
                    'tr' => [
                        'title' => 'Göreve Yeni Yorum: {task_title}',
                        'content' => '{commenter_name}, "{task_title}" görevi için yeni bir yorum ekledi: {comment_text}'
                    ],
                    'zh' => [
                        'title' => '任务的新评论: {task_title}',
                        'content' => '{commenter_name} 在任务“{task_title}”中添加了新评论: {comment_text}'
                    ],
                ]
            ],
            [
                'name' => 'New Invoice',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'New Invoice Created: {invoice_number}',
                        'content' => 'A new invoice {invoice_number} has been created for {client_name}. Amount: {amount}. Due date: {due_date}.'
                    ],
                    'es' => [
                        'title' => 'Nueva factura creada: {invoice_number}',
                        'content' => 'Se ha creado una nueva factura {invoice_number} para {client_name}. Monto: {amount}. Fecha de vencimiento: {due_date}.'
                    ],
                    'ar' => [
                        'title' => 'تم إنشاء فاتورة جديدة: {invoice_number}',
                        'content' => 'تم إنشاء الفاتورة {invoice_number} للعميل {client_name}. المبلغ: {amount}. تاريخ الاستحقاق: {due_date}.'
                    ],
                    'da' => [
                        'title' => 'Ny faktura oprettet: {invoice_number}',
                        'content' => 'En ny faktura {invoice_number} er blevet oprettet for {client_name}. Beløb: {amount}. Forfaldsdato: {due_date}.'
                    ],
                    'de' => [
                        'title' => 'Neue Rechnung erstellt: {invoice_number}',
                        'content' => 'Eine neue Rechnung {invoice_number} wurde für {client_name} erstellt. Betrag: {amount}. Fälligkeitsdatum: {due_date}.'
                    ],
                    'fr' => [
                        'title' => 'Nouvelle facture créée : {invoice_number}',
                        'content' => 'Une nouvelle facture {invoice_number} a été créée pour {client_name}. Montant : {amount}. Date d\'échéance : {due_date}.'
                    ],
                    'he' => [
                        'title' => 'חשבונית חדשה נוצרה: {invoice_number}',
                        'content' => 'נוצרה חשבונית חדשה {invoice_number} עבור {client_name}. סכום: {amount}. תאריך יעד: {due_date}.'
                    ],
                    'it' => [
                        'title' => 'Nuova fattura creata: {invoice_number}',
                        'content' => 'È stata creata una nuova fattura {invoice_number} per {client_name}. Importo: {amount}. Data di scadenza: {due_date}.'
                    ],
                    'ja' => [
                        'title' => '新しい請求書が作成されました: {invoice_number}',
                        'content' => '新しい請求書 {invoice_number} が {client_name} 向けに作成されました。金額: {amount}。期日: {due_date}。'
                    ],
                    'nl' => [
                        'title' => 'Nieuwe factuur aangemaakt: {invoice_number}',
                        'content' => 'Er is een nieuwe factuur {invoice_number} aangemaakt voor {client_name}. Bedrag: {amount}. Vervaldatum: {due_date}.'
                    ],
                    'pl' => [
                        'title' => 'Utworzono nową fakturę: {invoice_number}',
                        'content' => 'Utworzono nową fakturę {invoice_number} dla {client_name}. Kwota: {amount}. Termin płatności: {due_date}.'
                    ],
                    'pt' => [
                        'title' => 'Nova fatura criada: {invoice_number}',
                        'content' => 'Uma nova fatura {invoice_number} foi criada para {client_name}. Valor: {amount}. Data de vencimento: {due_date}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Nova fatura criada: {invoice_number}',
                        'content' => 'Uma nova fatura {invoice_number} foi criada para {client_name}. Valor: {amount}. Data de vencimento: {due_date}.'
                    ],
                    'ru' => [
                        'title' => 'Создан новый счет: {invoice_number}',
                        'content' => 'Создан новый счет {invoice_number} для {client_name}. Сумма: {amount}. Срок оплаты: {due_date}.'
                    ],
                    'tr' => [
                        'title' => 'Yeni Fatura Oluşturuldu: {invoice_number}',
                        'content' => '{client_name} için yeni bir {invoice_number} numaralı fatura oluşturuldu. Tutar: {amount}. Son ödeme tarihi: {due_date}.'
                    ],
                    'zh' => [
                        'title' => '新发票已创建: {invoice_number}',
                        'content' => '为 {client_name} 创建了新发票 {invoice_number}。金额: {amount}。到期日: {due_date}。'
                    ],
                ]
            ],
            [
                'name' => 'Invoice Status Updated',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'Invoice Status Updated: {invoice_number}',
                        'content' => 'Invoice {invoice_number} status has been updated to {status} by {updated_by}.'
                    ],
                    'es' => [
                        'title' => 'Estado de factura actualizado: {invoice_number}',
                        'content' => 'El estado de la factura {invoice_number} ha sido actualizado a {status} por {updated_by}.'
                    ],
                    'ar' => [
                        'title' => 'تم تحديث حالة الفاتورة: {invoice_number}',
                        'content' => 'تم تحديث حالة الفاتورة {invoice_number} إلى {status} بواسطة {updated_by}.'
                    ],
                    'da' => [
                        'title' => 'Fakturastatus opdateret: {invoice_number}',
                        'content' => 'Status for faktura {invoice_number} er blevet opdateret til {status} af {updated_by}.'
                    ],
                    'de' => [
                        'title' => 'Rechnungsstatus aktualisiert: {invoice_number}',
                        'content' => 'Der Status der Rechnung {invoice_number} wurde von {updated_by} auf {status} aktualisiert.'
                    ],
                    'fr' => [
                        'title' => 'Statut de facture mis à jour : {invoice_number}',
                        'content' => 'Le statut de la facture {invoice_number} a été mis à jour sur {status} par {updated_by}.'
                    ],
                    'he' => [
                        'title' => 'סטטוס החשבונית עודכן: {invoice_number}',
                        'content' => 'סטטוס החשבונית {invoice_number} עודכן ל-{status} על ידי {updated_by}.'
                    ],
                    'it' => [
                        'title' => 'Stato della fattura aggiornato: {invoice_number}',
                        'content' => 'Lo stato della fattura {invoice_number} è stato aggiornato a {status} da {updated_by}.'
                    ],
                    'ja' => [
                        'title' => '請求書のステータスが更新されました: {invoice_number}',
                        'content' => '請求書 {invoice_number} のステータスが {updated_by} により {status} に更新されました。'
                    ],
                    'nl' => [
                        'title' => 'Factuurstatus bijgewerkt: {invoice_number}',
                        'content' => 'De status van factuur {invoice_number} is bijgewerkt naar {status} door {updated_by}.'
                    ],
                    'pl' => [
                        'title' => 'Zaktualizowano status faktury: {invoice_number}',
                        'content' => 'Status faktury {invoice_number} został zaktualizowany na {status} przez {updated_by}.'
                    ],
                    'pt' => [
                        'title' => 'Status da fatura atualizado: {invoice_number}',
                        'content' => 'O status da fatura {invoice_number} foi atualizado para {status} por {updated_by}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Status da fatura atualizado: {invoice_number}',
                        'content' => 'O status da fatura {invoice_number} foi atualizado para {status} por {updated_by}.'
                    ],
                    'ru' => [
                        'title' => 'Статус счета обновлен: {invoice_number}',
                        'content' => 'Статус счета {invoice_number} был обновлен на {status} пользователем {updated_by}.'
                    ],
                    'tr' => [
                        'title' => 'Fatura Durumu Güncellendi: {invoice_number}',
                        'content' => '{invoice_number} numaralı faturanın durumu {updated_by} tarafından {status} olarak güncellendi.'
                    ],
                    'zh' => [
                        'title' => '发票状态已更新: {invoice_number}',
                        'content' => '发票 {invoice_number} 的状态已由 {updated_by} 更新为 {status}。'
                    ],
                ]
            ],

            [
                'name' => 'Expense Approval',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'Expense Approval Required: {expense_title}',
                        'content' => 'Expense "{expense_title}" for {expense_amount} requires your approval. Submitted by {submitted_by} for project {project_name}.'
                    ],
                    'es' => [
                        'title' => 'Aprobación de gasto requerida: {expense_title}',
                        'content' => 'El gasto "{expense_title}" por {expense_amount} requiere su aprobación. Enviado por {submitted_by} para el proyecto {project_name}.'
                    ],
                    'ar' => [
                        'title' => 'مطلوب الموافقة على المصروف: {expense_title}',
                        'content' => 'المصروف "{expense_title}" بمبلغ {expense_amount} يتطلب موافقتك. تم تقديمه بواسطة {submitted_by} للمشروع {project_name}.'
                    ],
                    'da' => [
                        'title' => 'Godkendelse af udgift påkrævet: {expense_title}',
                        'content' => 'Udgiften "{expense_title}" på {expense_amount} kræver din godkendelse. Indsendt af {submitted_by} for projektet {project_name}.'
                    ],
                    'de' => [
                        'title' => 'Ausgabenfreigabe erforderlich: {expense_title}',
                        'content' => 'Die Ausgabe "{expense_title}" über {expense_amount} erfordert Ihre Genehmigung. Eingereicht von {submitted_by} für das Projekt {project_name}.'
                    ],
                    'fr' => [
                        'title' => 'Approbation de dépense requise : {expense_title}',
                        'content' => 'La dépense "{expense_title}" pour {expense_amount} nécessite votre approbation. Soumis par {submitted_by} pour le projet {project_name}.'
                    ],
                    'he' => [
                        'title' => 'נדרש אישור הוצאה: {expense_title}',
                        'content' => 'הוצאה "{expense_title}" בסך {expense_amount} דורשת את אישורך. הוגש על ידי {submitted_by} עבור הפרויקט {project_name}.'
                    ],
                    'it' => [
                        'title' => 'Approvazione spesa richiesta: {expense_title}',
                        'content' => 'La spesa "{expense_title}" per {expense_amount} richiede la tua approvazione. Inviata da {submitted_by} per il progetto {project_name}.'
                    ],
                    'ja' => [
                        'title' => '経費承認が必要です: {expense_title}',
                        'content' => '経費「{expense_title}」({expense_amount}) は承認が必要です。提出者: {submitted_by}、プロジェクト: {project_name}。'
                    ],
                    'nl' => [
                        'title' => 'Goedkeuring uitgave vereist: {expense_title}',
                        'content' => 'Uitgave "{expense_title}" voor {expense_amount} vereist uw goedkeuring. Ingediend door {submitted_by} voor project {project_name}.'
                    ],
                    'pl' => [
                        'title' => 'Wymagana akceptacja wydatku: {expense_title}',
                        'content' => 'Wydatek "{expense_title}" na kwotę {expense_amount} wymaga Twojej akceptacji. Zgłoszone przez {submitted_by} dla projektu {project_name}.'
                    ],
                    'pt' => [
                        'title' => 'Aprovação de despesa necessária: {expense_title}',
                        'content' => 'A despesa "{expense_title}" no valor de {expense_amount} requer a sua aprovação. Submetido por {submitted_by} para o projeto {project_name}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Aprovação de despesa necessária: {expense_title}',
                        'content' => 'A despesa "{expense_title}" no valor de {expense_amount} requer sua aprovação. Enviado por {submitted_by} para o projeto {project_name}.'
                    ],
                    'ru' => [
                        'title' => 'Требуется утверждение расхода: {expense_title}',
                        'content' => 'Расход "{expense_title}" на сумму {expense_amount} требует вашего утверждения. Отправлено {submitted_by} для проекта {project_name}.'
                    ],
                    'tr' => [
                        'title' => 'Gider Onayı Gerekli: {expense_title}',
                        'content' => '"{expense_title}" adlı {expense_amount} tutarındaki gider onayınızı gerektiriyor. {submitted_by} tarafından {project_name} projesi için gönderildi.'
                    ],
                    'zh' => [
                        'title' => '需要报销审批: {expense_title}',
                        'content' => '报销 "{expense_title}"，金额 {expense_amount}，需要您的审批。由 {submitted_by} 提交，项目：{project_name}。'
                    ],
                ]
            ],
            [
                'name' => 'New Budget',
                'type' => 'info',
                'translations' => [
                    'en' => [
                        'title' => 'New Budget Created: {project_name}',
                        'content' => 'A new budget has been created for project "{project_name}" with total amount {total_budget}. Period: {period_type}.'
                    ],
                    'es' => [
                        'title' => 'Nuevo presupuesto creado: {project_name}',
                        'content' => 'Se ha creado un nuevo presupuesto para el proyecto "{project_name}" con un monto total de {total_budget}. Período: {period_type}.'
                    ],
                    'ar' => [
                        'title' => 'تم إنشاء ميزانية جديدة: {project_name}',
                        'content' => 'تم إنشاء ميزانية جديدة للمشروع "{project_name}" بمبلغ إجمالي {total_budget}. الفترة: {period_type}.'
                    ],
                    'da' => [
                        'title' => 'Nyt budget oprettet: {project_name}',
                        'content' => 'Et nyt budget er oprettet for projektet "{project_name}" med et samlet beløb på {total_budget}. Periode: {period_type}.'
                    ],
                    'de' => [
                        'title' => 'Neues Budget erstellt: {project_name}',
                        'content' => 'Ein neues Budget für das Projekt "{project_name}" mit einem Gesamtbetrag von {total_budget} wurde erstellt. Zeitraum: {period_type}.'
                    ],
                    'fr' => [
                        'title' => 'Nouveau budget créé : {project_name}',
                        'content' => 'Un nouveau budget a été créé pour le projet "{project_name}" avec un montant total de {total_budget}. Période : {period_type}.'
                    ],
                    'he' => [
                        'title' => 'נוצר תקציב חדש: {project_name}',
                        'content' => 'נוצר תקציב חדש עבור הפרויקט "{project_name}" בסכום כולל של {total_budget}. תקופה: {period_type}.'
                    ],
                    'it' => [
                        'title' => 'Nuovo budget creato: {project_name}',
                        'content' => 'È stato creato un nuovo budget per il progetto "{project_name}" con un importo totale di {total_budget}. Periodo: {period_type}.'
                    ],
                    'ja' => [
                        'title' => '新しい予算が作成されました: {project_name}',
                        'content' => 'プロジェクト「{project_name}」の新しい予算が作成されました。総額: {total_budget}。期間: {period_type}。'
                    ],
                    'nl' => [
                        'title' => 'Nieuw budget aangemaakt: {project_name}',
                        'content' => 'Er is een nieuw budget aangemaakt voor project "{project_name}" met een totaalbedrag van {total_budget}. Periode: {period_type}.'
                    ],
                    'pl' => [
                        'title' => 'Utworzono nowy budżet: {project_name}',
                        'content' => 'Utworzono nowy budżet dla projektu "{project_name}" o łącznej kwocie {total_budget}. Okres: {period_type}.'
                    ],
                    'pt' => [
                        'title' => 'Novo orçamento criado: {project_name}',
                        'content' => 'Um novo orçamento foi criado para o projeto "{project_name}" com o valor total de {total_budget}. Período: {period_type}.'
                    ],
                    'pt-BR' => [
                        'title' => 'Novo orçamento criado: {project_name}',
                        'content' => 'Um novo orçamento foi criado para o projeto "{project_name}" com o valor total de {total_budget}. Período: {period_type}.'
                    ],
                    'ru' => [
                        'title' => 'Создан новый бюджет: {project_name}',
                        'content' => 'Создан новый бюджет для проекта "{project_name}" на общую сумму {total_budget}. Период: {period_type}.'
                    ],
                    'tr' => [
                        'title' => 'Yeni Bütçe Oluşturuldu: {project_name}',
                        'content' => '"{project_name}" projesi için toplam {total_budget} tutarında yeni bir bütçe oluşturuldu. Dönem: {period_type}.'
                    ],
                    'zh' => [
                        'title' => '已创建新预算: {project_name}',
                        'content' => '项目 "{project_name}" 已创建新预算，总金额 {total_budget}。期间：{period_type}。'
                    ],
                ]
            ]

        ];

        foreach ($templates as $templateData) {
            $template = NotificationTemplate::updateOrCreate([
                'name' => $templateData['name'],
                'type' => $templateData['type'],
                'user_id' => auth()->id() ?? 1,
            ]);

            foreach ($templateData['translations'] as $lang => $translation) {
                NotificationTemplateLang::updateOrCreate([
                    'parent_id' => $template->id,
                    'lang' => $lang,
                    'title' => $translation['title'],
                    'content' => $translation['content'],
                ]);
            }

            // Create default English translations for other languages if not provided
            foreach ($supportedLanguages as $lang) {
                if (!isset($templateData['translations'][$lang])) {
                    NotificationTemplateLang::updateOrCreate([
                        'parent_id' => $template->id,
                        'lang' => $lang,
                        'title' => $templateData['translations']['en']['title'],
                        'content' => $templateData['translations']['en']['content'],
                    ]);
                }
            }
        }
    }
}