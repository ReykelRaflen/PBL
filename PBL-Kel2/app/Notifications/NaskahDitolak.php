<?php

namespace App\Notifications;

use App\Models\Naskah;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NaskahDitolak extends Notification implements ShouldQueue
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
            ->subject('Naskah Anda Ditolak')
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Kami informasikan bahwa naskah Anda telah ditolak oleh admin.')
            ->line('**Detail Naskah:**')
            ->line('Judul: ' . $this->naskah->judul)
            ->line('Tanggal Ditolak: ' . $this->naskah->direview_pada->format('d M Y H:i'))
            ->line('Reviewer: ' . ($this->naskah->reviewer->name ?? 'Admin'))
            ->when($this->naskah->catatan, function ($message) {
                return $message->line('**Alasan Penolakan:**')
                              ->line($this->naskah->catatan);
            })
            ->line('Anda dapat memperbaiki naskah dan mengirimkan kembali.')
            ->action('Lihat Detail Naskah', route('user.naskah.show', $this->naskah->id))
            ->line('Jangan menyerah! Perbaiki sesuai catatan dan kirim kembali.')
            ->salutation('Salam, Tim Admin');
    }

    /**
     * Get the database representation of the notification.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'naskah_ditolak',
            'title' => 'Naskah Ditolak',
            'message' => "Naskah '{$this->naskah->judul}' telah ditolak",
            'naskah_id' => $this->naskah->id,
            'naskah_judul' => $this->naskah->judul,
            'reviewer_name' => $this->naskah->reviewer->name ?? 'Admin',
            'direview_pada' => $this->naskah->direview_pada,
            'catatan' => $this->naskah->catatan,
            'action_url' => route('user.naskah.show', $this->naskah->id),
            'icon' => 'fas fa-times-circle',
            'color' => 'danger'
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
