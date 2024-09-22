<?php

namespace App\Services;

use App\Interfaces\ReportCsvInterface;
use App\Models\Complaince;
use App\Models\Event;
use App\Models\Inscription;
use App\Models\Reviews;
use App\Models\User;

class ReportCsvService implements ReportCsvInterface
{
    public function exportCsvUser(): \Illuminate\Http\Response
    {
        $data = User::select('id', 'name', 'email', 'date_birthday', 'gender', 'status', 'last_login')
            ->with('permissions')
            ->get()
            ->toArray();

        $csvData = [];
        $csvData[] = ['ID', 'Nome', 'Email', 'Data de Nascimento', 'Género', 'Status', 'Último Login', 'Permissões'];
        
        foreach ($data as $utils) {
            $typePermissions = $utils['permissions'] ? $utils['permissions']['type'] : 'Nenhuma permissão'; // Verifica se há permissão

            $csvData[] = [
                $utils['id'],
                $utils['name'],
                $utils['email'],
                $utils['date_birthday'],
                $utils['gender'],
                $utils['status'],
                $utils['last_login'],
                $typePermissions
            ];
        }

        $arquivoCsv = fopen('php://temp', 'r+');
        foreach ($csvData as $linha) {
            fputcsv($arquivoCsv, $linha);
        }

        rewind($arquivoCsv);
        $conteudoCsv = stream_get_contents($arquivoCsv);
        fclose($arquivoCsv);

        return response($conteudoCsv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="relatorio_usuarios.csv"');
    }

    public function exportCsvInscriptionReview(): \Illuminate\Http\Response
    {
        $data = Inscription::with(['user', 'event'])
            ->get()
            ->map(function ($inscription) {

                $review = Reviews::where('event_id', $inscription->event_id)
                    ->where('user_id', $inscription->user_id)
                    ->first();

                return [
                    'inscription_id' => $inscription->id,
                    'user_id' => $inscription->user_id,
                    'user_name' => $inscription->user->name,
                    'user_email' => $inscription->user->email,
                    'event_name' => $inscription->event->name,
                    'event_date' => $inscription->event->date,
                    'present' => $inscription->present,
                    'rating' => $review ? $review->rating : '',
                ];
            })
            ->toArray();

        $csvData = [];
        $csvData[] = ['Id Inscrição', 'Id Usuário', 'Nome Usuário', 'E-mail Usuário', 'Nome Evento', 'Data do Evento', 'Presente', 'Avaliação'];

        foreach ($data as $entry) {
            $csvData[] = [
                $entry['inscription_id'],
                $entry['user_id'],
                $entry['user_name'],
                $entry['user_email'],
                $entry['event_name'],
                $entry['event_date'],
                $entry['present'],
                $entry['rating'],
            ];
        }

        $arquivoCsv = fopen('php://temp', 'r+');
        foreach ($csvData as $linha) {
            fputcsv($arquivoCsv, $linha);
        }

        rewind($arquivoCsv);
        $conteudoCsv = stream_get_contents($arquivoCsv);
        fclose($arquivoCsv);

        return response($conteudoCsv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="relatorio_inscricoes_avaliacoes.csv"');
    }

    public function exportCsvEventsReport(): \Illuminate\Http\Response
    {
        $events = Event::with(['reviews'])
            ->get()
            ->map(function ($event) {
                $averageRating = $event->reviews->avg('rating') ?? 0;
                $numberOfReviews = $event->reviews->count();
                $numberOfInscribed = Inscription::where('event_id', $event->id)->count();
                $numberPresent = Inscription::where('event_id', $event->id)->where('present', true)->count();

                return [
                    'name' => $event->name,
                    'description' => $event->description,
                    'date' => $event->date,
                    'time' => $event->time,
                    'modality' => $event->modality,
                    'type' => $event->type,
                    'target_audience' => $event->target_audience,
                    'status' => $event->status,
                    'material' => $event->material,
                    'interest_area' => $event->interest_area,
                    'price' => $event->price,
                    'workload' => $event->workload,
                    'average_rating' => $averageRating,
                    'number_of_reviews' => $numberOfReviews,
                    'number_present' => $numberPresent,
                    'number_inscribed' => $numberOfInscribed,
                ];
            })
            ->toArray();

        $csvData = [];
        $csvData[] = ['Nome do Evento', 'Descrição', 'Data', 'Hora', 'Modalidade', 'Tipo', 'Público-alvo', 'Status', 'Material', 'Área de Interesse', 'Preço', 'Carga Horária', 'Média das Avaliações', 'Número de Avaliações', 'Número Presentes', 'Número Inscritos'];

        foreach ($events as $event) {
            $csvData[] = [
                $event['name'],
                $event['description'],
                $event['date'],
                $event['time'],
                $event['modality'],
                $event['type'],
                $event['target_audience'],
                $event['status'],
                $event['material'],
                $event['interest_area'],
                $event['price'],
                $event['workload'],
                $event['average_rating'],
                $event['number_of_reviews'],
                $event['number_present'],
                $event['number_inscribed'],
            ];
        }

        $arquivoCsv = fopen('php://temp', 'r+');
        foreach ($csvData as $linha) {
            fputcsv($arquivoCsv, $linha);
        }

        rewind($arquivoCsv);
        $conteudoCsv = stream_get_contents($arquivoCsv);
        fclose($arquivoCsv);

        return response($conteudoCsv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="relatorio_eventos.csv"');
    }

    public function exportCsvComplianceReport(): \Illuminate\Http\Response
    {
        $complaints = Complaince::all()->toArray();

        $csvData = [];
        $csvData[] = ['Nome', 'E-mail', 'Telefone', 'Descrição', 'Relação', 'Motivação', 'Navegador', 'Endereço IP'];
        
        foreach ($complaints as $complaint) {
            $csvData[] = [
                $complaint['name'],
                $complaint['email'],
                $complaint['phone_number'],
                $complaint['description'],
                $complaint['relation'],
                $complaint['motivation'],
                $complaint['browser'],
                $complaint['ip_address'],
            ];
        }

        $arquivoCsv = fopen('php://temp', 'r+');
        foreach ($csvData as $linha) {
            fputcsv($arquivoCsv, $linha);
        }

        rewind($arquivoCsv);
        $conteudoCsv = stream_get_contents($arquivoCsv);
        fclose($arquivoCsv);

        return response($conteudoCsv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="relatorio_reclamacoes.csv"');
    }
}