<?php
namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    public function run()
    {

        $permissionIds = [];
        $permissions = [
            'manage_users' => 'Gérer les utilisateurs',
            'manage_candidats' => 'Gérer les candidats',
            'view_dashboard' => 'Voir le tableau de bord',
            'upload_documents' => 'Télécharger des documents',
            'pass_quiz' => 'Passer le quiz',
            'creat_qst' => 'Créer des questions',
            'edit_qst' => 'Modifier des questions',
            'delete_qst' => 'Supprimer des questions',
            'creat_quize' => 'Créer des quiz',
            'edit_quize' => 'Modifier des quiz',
            'delete_quize' => 'Supprimer des quiz',
        ];
        foreach ($permissions as $id => $name) {
            Permission::create(['id' => $id]);
            $permissionIds[$id] = $id;
        }



    }
}