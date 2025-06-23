<?php

namespace App\Notifications;

use App\Models\Naskah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeadlineReminder extends Notification implements ShouldQueue
{
    use Queueable;

    protected $naskah;
    protected $daysLeft;

    /**
     * Create a new notification instance.
     */
    public function __construct(Naskah $naskah, int $daysLeft)
    {
        $this->naskah = $naskah;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->daysLeft > 0 
            ? "Pengingat: Deadline Naskah {$this->daysLeft} Hari Lagi"
            : "URGENT: Deadline Naskah Sudah Terlewat";

        $message = (new MailMessage)
            ->subject($subject)
            ->greeting('Halo ' . $notifiable->name . ',');

        if ($this->daysLeft > 0) {
            $message->line("Naskah Anda akan mencapai deadline dalam {$this->daysLeft} hari.");
        } else {
            $message->line("Naskah Anda telah melewati deadline sejak " . abs($this->daysLeft) . " hari yang lalu.");
        }

        return $message
            ->line('**Detail Naskah:**')
            ->line('Judul: ' . $this->naskah->judul)
            ->line('Status: ' . $this->naskah->status_label)
            ->line('Deadline: ' . $this->naskah->batas_waktu->format('d M Y'))
            ->action('Lihat Detail Naskah', route('user.naskah.show', $this->naskah->id))
            ->line($this->daysLeft > 0 
                ? 'Harap segera tindak lanjuti naskah Anda.' 
                : 'Silakan hubungi admin untuk perpanjangan deadline.')
            ->salutation('Salam, Tim Admin');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        $isOverdue = $this->daysLeft <= 0;
        
        return [
            'type' => $isOverdue ? 'deadline_overdue' : 'deadline_reminder',
            'title' => $isOverdue ? 'Deadline Terlewat' : 'Pengingat Deadline',
            'message' => $isOverdue 
                ? "Naskah '{$this->naskah->judul}' telah melewati deadline"
                : "Naskah '{$this->naskah->judul}' deadline {$this->daysLeft} hari lagi",
            'naskah_id' => $this->naskah->id,
            'naskah_judul' => $this->naskah->judul,
            'batas_waktu' => $this->naskah->batas_waktu,
            'days_left' => $this->daysLeft,
            'status' => $this->naskah->status,
            'action_url' => route('user.naskah.show', $this->naskah->id),
            'icon' => $isOverdue ? 'fas fa-exclamation-triangle' : 'fas fa-clock',
            'color' => $isOverdue ? 'danger' : 'warning'
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
