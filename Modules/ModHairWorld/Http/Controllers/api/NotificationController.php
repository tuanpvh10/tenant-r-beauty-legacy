<?php

namespace Modules\ModHairWorld\Http\Controllers\api;


use App\Http\Controllers\Controller;
use App\UploadedFile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    function count(Request $request){
        $rs = me()->unreadNotifications()->count();
        return \Response::json($rs);
    }

    function listItems(Request $request){
        /** @var Builder $ls */
        $ls = me()->notifications();
        $ls = $ls->paginate(10);
        $need_ids = [];
        foreach ($ls as $l){
            $data = $l->data;
            if($data['cover']){
                if(!in_array($data['cover'],$need_ids)){
                    $need_ids[] = $data['cover'];
                }
            }
        }
        $loaded_covers = [];
        /** @var UploadedFile[] $loaded */
        $loaded = UploadedFile::whereIn('id', $need_ids)->get();
        foreach ($loaded as $lad){
            $loaded_covers[$lad->id] = $lad->getThumbnailUrl('default', getNoThumbnailUrl());
        }

        /** @var LengthAwarePaginator $ls */
        $rs = [
            'currentPage' => $ls->currentPage(),
            'isLast' => $ls->currentPage() === $ls->lastPage(),
            'items' => array_map(function (DatabaseNotification $item) use($loaded_covers){
                $data = $item->data;
                $cover = false;
                $route = false;
                if(isset($data['cover'])){
                   if($data['cover']){
                        if(array_key_exists($data['cover'], $loaded_covers)){
                            $cover = $loaded_covers[$data['cover']];
                        }
                    }
                }
                if(isset($data['data'])){
                    if(isset($data['data']['route'])){
                        $route = $data['data']['route'];
                    }
                }
                $title = isset($data['mobile_title'])?$data['mobile_title']:'';
                $link = false;
                if (isset($data['link'])) {
                    $link = $data['link'];
                }
                return [
                    'id' => $item->id,
                    'cover' => $cover?$cover:getNoThumbnailUrl(),
                    'link' => $link,
                    'read' => $item->read(),
                    'date' => $item->created_at->format('d/m/Y'),
                    'title' => $title,
                    'route' => [
                        'routeName' => $route[0],
                        'params' => $route[1]
                    ],
                ];
            }, $ls->items())
        ];
        return \Response::json($rs);
    }

    function delete(Request $request){
        $ids = $request->get('ids', []);
        if($ids){
            me()->notifications()->whereIn('id', $ids)->delete();
        }
        return $this->count($request);
    }

    function read(Request $request, DatabaseNotification $notification){
        $notification->markAsRead();
        return $this->count($request);
    }
}