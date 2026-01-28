<?php
use App\Controller\RatingController;
use App\Model\Rating;

/** @var \App\Service\Router $router */

$controller = new RatingController();

if ($router->isPost()) {
    $input = json_decode(file_get_contents('php://input'), true);
    $showId = $input['show_id'] ?? null;
    $ratingValue = $input['value'] ?? null;

    
    if ($ratingValue !== null && $showId !== null && is_numeric($ratingValue) && is_numeric($showId)) {
        $rating = new Rating();
        $rating->setValue((float) $ratingValue);
        $rating->setShowId((int) $showId);
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