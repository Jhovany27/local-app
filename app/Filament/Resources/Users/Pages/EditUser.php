<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use App\Models\Repartidor;
use App\Models\RoleUser;
use App\Models\Tienda;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected array $rolesAntes = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->rolesAntes = RoleUser::where('user_id', $this->record->id)
            ->pluck('usr_fk_rol')
            ->map(fn($id) => (int)$id)
            ->toArray();

        return $data;
    }

    //  Capturar roles JUSTO ANTES de que Filament los guarde
    protected function beforeSave(): void
    {
        $this->rolesAntes = RoleUser::where('user_id', $this->record->id)
            ->pluck('usr_fk_rol')
            ->map(fn($id) => (int)$id)
            ->toArray();
    }

    protected function afterSave(): void
    {
        $record = $this->record->fresh();

        $rolesDespues = RoleUser::where('user_id', $record->id)
            ->pluck('usr_fk_rol')
            ->map(fn($id) => (int)$id)
            ->toArray();

        $antes   = collect($this->rolesAntes);
        $despues = collect($rolesDespues);

        $agregados = $despues->diff($antes);
        $quitados  = $antes->diff($despues);

        // ── ROL TIENDA (id=4) ─────────────────────────────
        if ($quitados->contains(4)) {
            $record->tiendas()->update(['tie_estado' => Tienda::ESTADO_RECHAZADA]);
            Notification::make()
                ->title('Rol tienda quitado')
                ->body('Todas sus tiendas fueron desactivadas.')
                ->warning()->send();
        }

        if ($agregados->contains(4)) {
            $record->tiendas()->update(['tie_estado' => Tienda::ESTADO_APROBADA]);
            Notification::make()
                ->title('Rol tienda asignado')
                ->body('Sus tiendas fueron reactivadas.')
                ->success()->send();
        }

        // ── ROL REPARTIDOR (id=3) ─────────────────────────
        if ($quitados->contains(3)) {
            Repartidor::where('user_id', $record->id)->update(['rep_estado' => 0]);
            Notification::make()
                ->title('Rol repartidor quitado')
                ->body('Su cuenta de repartidor fue desactivada.')
                ->warning()->send();
        }

        if ($agregados->contains(3)) {
            Repartidor::where('user_id', $record->id)->update(['rep_estado' => 1]);
            Notification::make()
                ->title('Rol repartidor asignado')
                ->body('Su cuenta de repartidor fue activada.')
                ->success()->send();
        }

           $this->redirect(static::getResource()::getUrl('edit', ['record' => $record->getKey()]));
    }
}
