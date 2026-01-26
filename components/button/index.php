<?php

function Button($icon, $text, $onclick, $class = '', $size = 'medium', $style = 'primary', $radius = 'normal')
{
    // Base classes
    $buttonClasses = ['btn', 'button'];

    // Style class
    if ($style === 'primary') {
        $buttonClasses[] = 'btn-primary';
    } elseif ($style === 'subtle') {
        $buttonClasses[] = 'btn-ghost';
    }

    // Size class
    if ($size === 'small') {
        $buttonClasses[] = 'btn-sm';
    } elseif ($size === 'large') {
        $buttonClasses[] = 'btn-lg';
    }
    
    // Radius class
    if ($radius === 'round') {
        $buttonClasses[] = 'button-round';
    }

    // Add custom classes
    if ($class) {
        $buttonClasses[] = $class;
    }
    
    $classString = implode(' ', $buttonClasses);

    echo '<button class="' . $classString . '" onclick="' . $onclick . '">';
    if ($icon) {
        echo '<i class="fa-solid fa-' . $icon . '"></i>';
    }
    echo '<span>' . $text . '</span>';
    echo '</button>';
}
