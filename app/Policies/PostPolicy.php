<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    // Voir la liste des posts → tout le monde (même non connecté)
    public function viewAny(?User $user): bool
    {
        return true;
    }

    // Voir un post → tout le monde
    public function view(?User $user, Post $post): bool
    {
        return true;
    }

    // Créer un post → uniquement connecté
    public function create(User $user): bool
    {
        return true;
    }

    // Modifier → uniquement l'auteur du post
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    // Supprimer → uniquement l'auteur du post
    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    // Restore et forceDelete → interdit
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
}
