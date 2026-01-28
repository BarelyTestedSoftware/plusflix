<?php
    $id = $params['id'];
    $averageRating = $params['averageRating'] ?? null;
    $numberOfRatings = $params['numberOfRatings'] ?? 0;
?>

<div class="rate-component">
    <div class="star-rating-widget" onchange="submitRating()">
    <input type="radio" name="rating" id="star-5.0" value="5.0"><label for="star-5.0" class="half-right"></label>
    <input type="radio" name="rating" id="star-4.5" value="4.5"><label for="star-4.5" class="half-left"></label>

    <input type="radio" name="rating" id="star-4.0" value="4.0"><label for="star-4.0" class="half-right"></label>
    <input type="radio" name="rating" id="star-3.5" value="3.5"><label for="star-3.5" class="half-left"></label>

    <input type="radio" name="rating" id="star-3.0" value="3.0"><label for="star-3.0" class="half-right"></label>
    <input type="radio" name="rating" id="star-2.5" value="2.5"><label for="star-2.5" class="half-left"></label>

    <input type="radio" name="rating" id="star-2.0" value="2.0"><label for="star-2.0" class="half-right"></label>
    <input type="radio" name="rating" id="star-1.5" value="1.5"><label for="star-1.5" class="half-left"></label>

    <input type="radio" name="rating" id="star-1.0" value="1.0"><label for="star-1.0" class="half-right"></label>
    <input type="radio" name="rating" id="star-0.5" value="0.5"><label for="star-0.5" class="half-left"></label>
    </div>
    
    <?php if ($averageRating !== null): ?>
        <div class="rating-stats">
            <span class="rating-value"><?= number_format((float) $averageRating, 1) ?></span>
            <span class="rating-separator">/</span>
            <span class="rating-max">5</span>
            <?php if ($numberOfRatings > 0): ?>
                <span class="rating-count">(<?= (int) $numberOfRatings ?> <?= $numberOfRatings === 1 ? 'ocena' : 'ocen' ?>)</span>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>


<script>
    const showId = <?= e($id); ?>;
    
    // Check if user has already rated this show
    function getExistingRating() {
        const ratings = JSON.parse(localStorage.getItem('user_ratings') || '{}');
        return ratings[showId] || null;
    }
    
    // Save rating to localStorage
    function saveRatingToStorage(ratingId, value) {
        const ratings = JSON.parse(localStorage.getItem('user_ratings') || '{}');
        ratings[showId] = { rating_id: ratingId, value: value };
        localStorage.setItem('user_ratings', JSON.stringify(ratings));
    }
    
    // Load existing rating on page load
    document.addEventListener('DOMContentLoaded', () => {
        const existingRating = getExistingRating();
        if (existingRating) {
            // Convert to string for comparison with input values
            const ratingStr = String(existingRating.value);
            const input = document.querySelector(`input[name="rating"][value="${ratingStr}"]`);
            if (input) {
                input.checked = true;
            } else {
                // Debug: log if input not found
                console.log('Rating input not found for value:', ratingStr);
                console.log('Available values:', Array.from(document.querySelectorAll('input[name="rating"]')).map(i => i.value));
            }
        }
    });
    
    function submitRating() {
        const selectedRating = document.querySelector('input[name="rating"]:checked');
        if (!selectedRating) {
            alert('Proszę wybrać ocenę przed wysłaniem.');
            return;
        }
        
        const ratingValue = parseFloat(selectedRating.value);
        const ratingValueStr = selectedRating.value;
        const existingRating = getExistingRating();
        
        // Decide whether to create or edit
        const endpoint = existingRating ? '/rate/edit' : '/rate/add';
        const payload = existingRating 
            ? { rating_id: existingRating.rating_id, value: ratingValue, show_id: showId }
            : { show_id: showId, value: ratingValue };
        
        fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then((res) => res.json())
        .then((data) => {
            if (data.success) {
                saveRatingToStorage(data.id, ratingValueStr);
                location.reload();
            } else {
                alert('Błąd: ' + (data.message || 'Nie udało się zapisać oceny'));
            }
        })
        .catch((error) => {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas zapisywania oceny.');
        });
    }
</script>