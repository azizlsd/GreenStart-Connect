<?php require_once __DIR__.'/../layouts/header.php'; ?>

<div class="container">
    <h1>Répondre au Feedback #<?= htmlspecialchars($_GET['feedback_id']) ?></h1>
    
    <?php if (!empty($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($_SESSION['errors'] as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form id="responseForm" action="/responses/store" method="POST">
        <input type="hidden" name="feedback_id" value="<?= htmlspecialchars($_GET['feedback_id']) ?>">
        
        <div class="form-group">
            <label for="response_type">Type de réponse:</label>
            <select class="form-control" id="response_type" name="response_type" required>
                <option value="">Sélectionnez un type</option>
                <option value="solution" <?= old('response_type') === 'solution' ? 'selected' : '' ?>>Solution</option>
                <option value="information" <?= old('response_type') === 'information' ? 'selected' : '' ?>>Information</option>
                <option value="rejection" <?= old('response_type') === 'rejection' ? 'selected' : '' ?>>Rejet</option>
                <option value="follow_up" <?= old('response_type') === 'follow_up' ? 'selected' : '' ?>>Suivi</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="content">Réponse:</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?= old('content') ?></textarea>
        </div>
        
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="is_public" name="is_public" <?= old('is_public') ? 'checked' : '' ?>>
            <label class="form-check-label" for="is_public">Rendre cette réponse publique</label>
        </div>
        
        <button type="submit" class="btn btn-primary">Envoyer la réponse</button>
    </form>
</div>

<script src="/assets/js/validation.js"></script>

<?php require_once __DIR__.'/../layouts/footer.php'; ?>