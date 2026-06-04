<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class GenericModuleNotification extends Notification
{
    use Queueable;

    protected $moduleType;
    protected $moduleId;
    protected $title;
    protected $body;
    protected $childIds;
    protected $createdBy;
    protected $recipientType; // 'parent' or 'staff'

    public function __construct(
        $moduleType,
        $moduleId,
        $title,
        $body,
        $childIds,
        $createdBy,
        $recipientType = 'parent'
    ) {
        $this->moduleType = $moduleType;
        $this->moduleId = $moduleId;
        $this->title = $title;
        $this->body = $body;
        $this->childIds = $childIds;
        $this->createdBy = $createdBy;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => $this->title,
            'message' => $this->body,
            'type' => $this->moduleType,
            'module_id' => $this->moduleId,
            'child_ids' => is_array($this->childIds) ? implode(',', $this->childIds) : $this->childIds,
            'created_by' => $this->createdBy,
            'recipient_type' => $this->recipientType,
            'icon' => $this->getIcon(),
            'objective' => $this->body,
            'url' => $this->getDeeplink(),
        ];
    }

    /**
     * Get the icon based on module type.
     *
     * @return string
     */
    protected function getIcon()
    {
        $icons = [
            'observation' => 'fa fa-binoculars',
            'reflection' => 'fa fa-lightbulb',
            'snapshot' => 'fa fa-camera',
            'diary' => 'fa fa-book',
            'announcement' => 'fa fa-bullhorn',
            'event' => 'fa fa-calendar',
            'programplan' => 'fa fa-tasks',
        ];

        return $icons[$this->moduleType] ?? 'fa fa-bell';
    }

    /**
     * Get the deeplink URL for the module.
     *
     * @return string
     */
    protected function getDeeplink()
    {
        $routes = [
            'observation' => "/observations/{$this->moduleId}",
            'reflection' => "/reflections/{$this->moduleId}",
            'snapshot' => "/snapshots/{$this->moduleId}",
            'diary' => "/diary/entries/{$this->moduleId}",
            'announcement' => "/announcements/{$this->moduleId}",
            'event' => "/events/{$this->moduleId}",
            'programplan' => "/program-plans/{$this->moduleId}",
        ];

        return $routes[$this->moduleType] ?? "#";
    }
}
