<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentHistory extends Model
{
    use HasFactory;

    /**
     * Campos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'comment_id',  // ID do comentário relacionado
        'content',     // Conteúdo da versão antiga do comentário
        'edited_at',   // Data/hora da edição
    ];

    /**
     * Relacionamento com o modelo Comment.
     * Um histórico de edição pertence a um único comentário.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}