<?php

namespace App\Services;

use App\Interfaces\ReportCsvInterface;
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
}