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
    protected $creatorName;

    public function __construct(
        $moduleType,
        $moduleId,
        $title,
        $body,
        $childIds,
        $createdBy,
        $recipientType = 'parent',
        $creatorName = null
    ) {
        $this->moduleType = $moduleType;
        $this->moduleId = $moduleId;
        $this->title = $title;
        $this->body = $body;
        $this->childIds = $childIds;
        $this->createdBy = $createdBy;
        $this->recipientType = $recipientType;
        $this->creatorName = $creatorName;
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
        // Generate role-specific messages
        $title = 'New ' . ucfirst($this->moduleType) . ' Added';
        $message = $this->body;

        if ($this->recipientType === 'staff') {
            // Staff message: "You are tagged in a new observation by (creator name)"
            $creatorName = $this->creatorName ?? 'A colleague';
            $message = "You are tagged in a new {$this->moduleType} by {$creatorName}";
        } else {
            // Parent message: "A new observation has been added for (child name)"
            $childNames = $this->getChildNames();
            if ($childNames) {
                $message = "A new " . strtolower($this->moduleType) . " has been added for {$childNames}";
            }
        }

        return [
            'title' => $title,
            'message' => $message,
            'type' => $this->moduleType,
            'module_id' => $this->moduleId,
            'child_ids' => is_array($this->childIds) ? implode(',', $this->childIds) : $this->childIds,
            'created_by' => $this->createdBy,
            'recipient_type' => $this->recipientType,
            'icon' => $this->getIcon(),
            'objective' => $message,
            'url' => $this->getDeeplink(),
        ];
    }

    /**
     * Get child names from child IDs.
     *
     * @return string
     */
    protected function getChildNames()
    {
        if (empty($this->childIds)) {
            return null;
        }

        try {
            $childIds = is_array($this->childIds) 
                ? $this->childIds 
                : array_filter(explode(',', (string)$this->childIds));
            
            $children = \App\Models\Child::whereIn('id', $childIds)
                ->select('id', 'name', 'lastname')
                ->get();

            if ($children->isEmpty()) {
                return null;
            }

            return $children->map(function ($child) {
                return trim($child->name . ' ' . ($child->lastname ?? ''));
            })->implode(', ');
        } catch (\Exception $e) {
            \Log::warning('Could not fetch child names in notification', [
                'child_ids' => $this->childIds,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
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
            'observation' => "/observation/{$this->moduleId}",
            'reflection' => "/daily-reflections/{$this->moduleId}",
            'snapshot' => "/snapshots",
            'diary' => "/daily-diary",
            'announcement' => "/events/{$this->moduleId}",
            'event' => "/events/{$this->moduleId}",
        ];

        return $routes[$this->moduleType] ?? "#";
    }
}
