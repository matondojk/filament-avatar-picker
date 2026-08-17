<?php
$dirs = glob(__DIR__ . '/resources/lang/*');
foreach($dirs as $dir) {
    $file = $dir . '/avatar.php';
    if (!file_exists($file)) continue;
    
    $content = file_get_contents($file);
    
    // Remove the old upload_prompt if it exists
    $content = preg_replace("/\n\s*'upload_prompt'\s*=>\s*'.*?',?/i", "", $content);
    
    $lang = basename($dir);
    $translation = '';
    
    $span = '<span class="filament-avatar-picker-browse-link">%s</span>';
    
    switch($lang) {
        case 'pt':
        case 'pt_BR':
            $translation = 'Selecione um avatar ou ' . sprintf($span, 'Clique aqui');
            break;
        case 'es':
            $translation = 'Selecciona un avatar o ' . sprintf($span, 'Haz clic aquí');
            break;
        case 'fr':
            $translation = 'Sélectionnez un avatar ou ' . sprintf($span, 'Cliquez ici');
            break;
        case 'it':
            $translation = 'Seleziona un avatar o ' . sprintf($span, 'Fai clic qui');
            break;
        case 'de':
            $translation = 'Wählen Sie einen Avatar oder ' . sprintf($span, 'Klicken Sie hier');
            break;
        case 'ru':
            $translation = 'Выберите аватар или ' . sprintf($span, 'Нажмите здесь');
            break;
        case 'ja':
            $translation = 'アバターを選択するか、' . sprintf($span, 'ここをクリック') . 'してください';
            break;
        case 'zh_CN':
            $translation = '选择一个头像或' . sprintf($span, '点击这里');
            break;
        case 'hi':
            $translation = 'एक अवतार चुनें या ' . sprintf($span, 'यहां क्लिक करें');
            break;
        case 'ar':
            $translation = 'حدد صورة رمزية أو ' . sprintf($span, 'انقر هنا');
            break;
        default:
            $translation = 'Pick an avatar or ' . sprintf($span, 'Browse');
    }
    
    // Replace the last bracket with the new key
    $content = preg_replace('/\];/', "    'upload_prompt' => '" . addslashes($translation) . "',\n];", $content);
    file_put_contents($file, $content);
}
echo "Done!\n";
