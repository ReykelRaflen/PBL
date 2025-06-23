<?php

namespace App\Notifications;

use App\Models\Naskah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Notifications\Notification;

class NaskahDisetujui extends Notification implements ShouldQueue
{
    use Queueable;

    protected $naskah;

    /**
     * Create a new notification instance.
     */
    public function __construct(Naskah $naskah)
    {
        $this->naskah = $naskah;
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
        return (new MailMessage)
            ->subject('Naskah Anda Telah Disetujui')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kabar baik! Naskah Anda telah disetujui oleh admin.')
            ->line('**Detail Naskah:**')
            ->line('Judul: ' . $this->naskah->judul)
            ->line('Tanggal Disetujui: ' . $this->naskah->direview_pada->format('d M Y H:i'))
            ->line('Reviewer: ' . ($this->naskah->reviewer->name ?? 'Admin'))
            ->when($this->naskah->catatan, function ($message) {
                return $message->line('**Catatan dari Reviewer:**')
                              ->line($this->naskah->catatan);
            })
            ->action('Lihat Detail Naskah', route('user.naskah.show', $this->naskah->id))
            ->line('Terima kasih telah menggunakan sistem kami!')
            ->salutation('Salam, Tim Admin');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'naskah_disetujui',
            'title' => 'Naskah Disetujui',
            'message' => "Naskah '{$this->naskah->judul}' telah disetujui",
            'naskah_id' => $this->naskah->id,
            'naskah_judul' => $this->naskah->judul,
            'reviewer_name' => $this->naskah->reviewer->name ?? 'Admin',
            'direview_pada' => $this->naskah->direview_pada,
            'catatan' => $this->naskah->catatan,
            'action_url' => route('user.naskah.show', $this->naskah->id),
            'icon' => 'fas fa-check-circle',
            'color' => 'success'
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
