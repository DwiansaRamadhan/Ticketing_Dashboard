<?php

namespace App\Exports;

use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TaskExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $task = Task::select('title', 'deskripsi', 'tanggal', 'customer', 'tipe', 'kategori', 'completed_at')
        ->with('user')
        // $task = Task::with('user')
        ->get();
        //Log::info(json_encode($task));
        $data = array();
        Task::with('user')->chunk(50, function($items) use (&$data){
            Log::info(json_encode($items));
            foreach($items as $a){
                $param['name'] = $a->user->name;
                $param['title'] = $a->title;
                $param['deskripsi'] = $a->deskripsi;
                $param['tanggal'] = $a->tanggal;
                $param['customer'] = $a->customer;
                $param['tipe'] = $a->tipe;
                $param['kategori'] = $a->kategori;
                $param['completed_at'] = $a->completed_at;
                // $param_enc = json_encode($param);
                // $param_dec = json_decode($param_enc);
                // $data[] = $param_dec;
                $data[] = $param;
            }
        });

        return collect($data);
    }

    public function headings(): array
    {
        return ['Nama','Judul', 'Deskripsi', 'Tanggal', 'Customer', 'Tipe', 'Kategori', 'Tanggal Selesai'];
    }
}
