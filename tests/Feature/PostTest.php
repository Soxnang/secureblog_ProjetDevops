<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    // =============================================
    // SCÉNARIO 1 — Accès public à la liste
    // =============================================
    public function test_visiteur_peut_voir_la_liste_des_articles(): void
    {
        // ARRANGE — Préparer les données
        $user = User::factory()->create();
        Post::factory()->create([
            'user_id'   => $user->id,
            'published' => true,
        ]);

        // ACT — Faire la requête
        $response = $this->get('/posts');

        // ASSERT — Vérifier le résultat
        $response->assertStatus(200);
    }

    // =============================================
    // SCÉNARIO 2 — Redirection si non connecté
    // =============================================
    public function test_visiteur_non_connecte_est_redirige_vers_login(): void
    {
        $response = $this->get('/posts/create');

        // 302 = redirection vers /login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    // =============================================
    // SCÉNARIO 3 — Validation formulaire
    // =============================================
    public function test_creation_article_avec_titre_vide_echoue(): void
    {
        // ARRANGE — Utilisateur connecté
        $user = User::factory()->create();

        // ACT — Envoyer formulaire avec titre vide
        $response = $this->actingAs($user)->post('/posts', [
            'title'   => '',
            'content' => 'Contenu suffisamment long pour passer la validation',
        ]);

        // ASSERT — 422 erreur de validation
        $response->assertStatus(302);

        $response->assertSessionHasErrors(["title"]);

        // Vérifier que rien n'est en BDD
        $this->assertDatabaseCount('posts', 0);
    }

    // =============================================
    // SCÉNARIO 4 — Autorisation (403)
    // =============================================
    public function test_utilisateur_ne_peut_pas_modifier_article_dautrui(): void
    {
        // ARRANGE — Deux utilisateurs différents
        $auteur  = User::factory()->create();
        $inconnu = User::factory()->create();

        $post = Post::factory()->create([
            'user_id'   => $auteur->id,
            'published' => true,
        ]);

        // ACT — L'inconnu essaie de modifier le post de l'auteur
        $response = $this->actingAs($inconnu)->patch(
            "/posts/{$post->slug}",
            [
                'title'   => 'Titre modifié par un inconnu',
                'content' => 'Contenu modifié par un inconnu',
            ]
        );

        // ASSERT — 403 Forbidden
        $response->assertStatus(403);

        // Vérifier que le post n'a pas changé
        $this->assertDatabaseHas('posts', [
            'id'    => $post->id,
            'title' => $post->title,
        ]);
    }

    // =============================================
    // SCÉNARIO 5 — Création réussie
    // =============================================
    public function test_utilisateur_connecte_peut_creer_un_article(): void
    {
        // ARRANGE
        $user = User::factory()->create();

        // ACT
        $response = $this->actingAs($user)->post('/posts', [
            'title'   => 'Mon premier article de blog',
            'content' => 'Ceci est le contenu de mon article avec suffisamment de texte.',
        ]);

        // ASSERT — 302 redirection vers le post créé
        $response->assertStatus(302);

        // Vérifier que le post est en BDD
        $this->assertDatabaseHas('posts', [
            'title'   => 'Mon premier article de blog',
            'user_id' => $user->id,
        ]);
    }
}
