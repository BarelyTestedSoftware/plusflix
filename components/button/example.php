<?php
// Example usage of the Button component, can be mounted in a test page for checxking
// because I was doing it blind..

require_once __DIR__ . '/index.php';
?>

<div style="display: flex; flex-direction: column; gap: 1rem; padding: 2rem; align-items: flex-start; background-color: #141414; border-radius: 8px;">
    <div>
        <h2 style="color: white; margin-bottom: 1rem;">Buttons</h2>
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <?php Button('star', 'Small Primary', 'alert(\'Clicked!\')', '', 'small', 'primary', 'normal'); ?>
            <?php Button('heart', 'Medium Primary', 'alert(\'Clicked!\')', '', 'medium', 'primary', 'normal'); ?>
            <?php Button('play', 'Large Primary', 'alert(\'Clicked!\')', '', 'large', 'primary', 'normal'); ?>
        </div>
    </div>
    <div style="margin-top: 1rem;">
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <?php Button('thumbs-up', 'Small Subtle', 'alert(\'Clicked!\')', '', 'small', 'subtle', 'normal'); ?>
            <?php Button('plus', 'Medium Subtle', 'alert(\'Clicked!\')', '', 'medium', 'subtle', 'normal'); ?>
            <?php Button('check', 'Large Subtle', 'alert(\'Clicked!\')', '', 'large', 'subtle', 'normal'); ?>
        </div>
    </div>
    <div style="margin-top: 1rem;">
        <h2 style="color: white; margin-bottom: 1rem;">Round Buttons</h2>
        <div style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <?php Button('pen', 'Small Round', 'alert(\'Clicked!\')', '', 'small', 'primary', 'round'); ?>
            <?php Button('trash', 'Medium Round', 'alert(\'Clicked!\')', '', 'medium', 'subtle', 'round'); ?>
            <?php Button('share', 'Large Round', 'alert(\'Clicked!\')', '', 'large', 'primary', 'round'); ?>
        </div>
    </div>
</div>