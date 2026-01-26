<?php
use App\Controller\RatingController;
use App\Model\Rating;

/** @var \App\Service\Router $router */

$controller = new RatingController();

if ($router->isPost()) {
    $input = json_decode(file_get_contents('php://input'), true);
    $ratingId = $input['rating_id'] ?? null;
    $showId = $input['show_id'] ?? null;
    $ratingValue = $input['value'] ?? null;

    if ($ratingId !== null && $ratingValue !== null && is_numeric($ratingValue) && is_numeric($ratingId)) {
        $rating = Rating::find((int) $ratingId);
        
        if (!$rating) {
            header('Content-Type: application/json');
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Rating not found']);
            exit;
        }
        
        $rating->setValue((float) $ratingValue);
        if ($showId !== null && is_numeric($showId)) {
            $rating->setShowId((int) $showId);
        }
        $rating->save();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'id' => $rating->getId()]);
        exit;
    } else {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid input']);
        exit;
    }
}