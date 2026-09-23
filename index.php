<?php
declare(strict_types=1);

$path=$_GET['path']??'';
$path=trim(rawurldecode($path),'/');

if($path===''||str_contains($path,"\0")||preg_match('#(^|/)\.\.(/|$)#',$path)){
    http_response_code(403);
    exit('Access denied');
}

if(preg_match('#(^|/)raw(?:/|$)#i',$path)){
    http_response_code(403);
    exit('Invalid target');
}

$scheme=(!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!=='off')?'https':'http';
$host=$_SERVER['HTTP_HOST']??'localhost';
$url=$scheme.'://'.$host.'/'.$path;

function fetchUrl(string $url,string $userAgent):array{
    $ch=curl_init($url);

    curl_setopt_array($ch,[
        CURLOPT_RETURNTRANSFER=>true,
        CURLOPT_FOLLOWLOCATION=>true,
        CURLOPT_MAXREDIRS=>10,
        CURLOPT_HEADER=>false,
        CURLOPT_USERAGENT=>$userAgent,
        CURLOPT_ENCODING=>'',
        CURLOPT_TIMEOUT=>20
    ]);

    $content=curl_exec($ch);
    $status=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);

    curl_close($ch);

    return [$content,$status];
}

function detectLicense(string $content,string $filename):string{
    $text=strtolower($content);
    $text=preg_replace('/\s+/',' ',$text)??$text;

    if(str_contains($text,'survivalier license'))return'Survivalier License';
    if(str_contains($text,'gnu affero general public license'))return'GNU AGPL';
    if(str_contains($text,'gnu lesser general public license'))return'GNU LGPL';
    if(str_contains($text,'gnu general public license'))return'GNU GPL';

    if(
        str_contains($text,'apache license')&&
        preg_match('/version\s*2(?:\.0)?/i',$content)
    ){
        return'Apache 2.0';
    }

    if(
        str_contains($text,'mozilla public license')&&
        str_contains($text,'2.0')
    ){
        return'MPL 2.0';
    }

    if(
        str_contains($text,'eclipse public license')&&
        str_contains($text,'2.0')
    ){
        return'EPL 2.0';
    }

    if(
        str_contains($text,'mit license')||
        preg_match('/permission is hereby granted,\s*free of charge/i',$content)
    ){
        return'MIT';
    }

    if(
        str_contains($text,'bsd 3-clause')||
        str_contains($text,'redistribution and use in source and binary forms')
    ){
        if(
            str_contains($text,'neither the name of')||
            str_contains($text,'endorse or promote products')
        ){
            return'BSD 3-Clause';
        }

        return'BSD';
    }

    if(
        str_contains($text,'bsd 2-clause')||
        str_contains($text,'simplified bsd')
    ){
        return'BSD 2-Clause';
    }

    if(
        str_contains($text,'isc license')||
        str_contains(
            $text,
            'permission to use, copy, modify, and/or distribute this software for any purpose'
        )
    ){
        return'ISC';
    }

    if(
        str_contains($text,'the unlicense')||
        str_contains(
            $text,
            'this is free and unencumbered software released into the public domain'
        )
    ){
        return'Unlicense';
    }

    if(
        str_contains($text,'zlib license')||
        str_contains(
            $text,
            'this software is provided "as-is", without any express or implied warranty'
        )
    ){
        return'zlib';
    }

    if(str_contains($text,'boost software license'))return'Boost';

    if(
        str_contains(
            $text,
            'do what the fuck you want to public license'
        )
    ){
        return'WTFPL';
    }

    $name=strtolower($filename);

    if(str_contains($name,'survivalier'))return'Survivalier License';
    if(str_contains($name,'mit'))return'MIT';
    if(str_contains($name,'agpl'))return'GNU AGPL';
    if(str_contains($name,'lgpl'))return'GNU LGPL';
    if(str_contains($name,'gpl'))return'GNU GPL';
    if(str_contains($name,'apache'))return'Apache';
    if(str_contains($name,'mpl'))return'MPL';
    if(str_contains($name,'bsd'))return'BSD';
    if(str_contains($name,'isc'))return'ISC';

    return$filename;
}

/*
 * Détection automatique du langage.
 *
 * Priorité :
 * 1. Nom particulier du fichier
 * 2. Extension
 * 3. Contenu du fichier
 * 4. plaintext
 */
function detectLanguage(string $path,string $source=''):string{
    $filename=strtolower(basename($path));
    $extension=strtolower(pathinfo($filename,PATHINFO_EXTENSION));

    $languages=[
        'html'=>'xml',
        'htm'=>'xml',
        'xhtml'=>'xml',

        'css'=>'css',
        'scss'=>'scss',
        'sass'=>'scss',
        'less'=>'less',

        'js'=>'javascript',
        'mjs'=>'javascript',
        'cjs'=>'javascript',
        'jsx'=>'javascript',

        'ts'=>'typescript',
        'tsx'=>'typescript',

        'php'=>'php',

        'py'=>'python',
        'pyw'=>'python',

        'c'=>'c',
        'h'=>'c',
        'cpp'=>'cpp',
        'cc'=>'cpp',
        'cxx'=>'cpp',
        'hpp'=>'cpp',
        'hh'=>'cpp',

        'java'=>'java',

        'cs'=>'csharp',

        'go'=>'go',

        'rs'=>'rust',

        'rb'=>'ruby',

        'swift'=>'swift',

        'kt'=>'kotlin',
        'kts'=>'kotlin',

        'sh'=>'bash',
        'bash'=>'bash',
        'zsh'=>'bash',

        'sql'=>'sql',

        'json'=>'json',
        'jsonc'=>'json',

        'xml'=>'xml',
        'svg'=>'xml',

        'yaml'=>'yaml',
        'yml'=>'yaml',

        'md'=>'markdown',
        'markdown'=>'markdown',

        'txt'=>'plaintext',
        'text'=>'plaintext',

        'ini'=>'ini',
        'conf'=>'ini',

        'toml'=>'ini',

        'dockerfile'=>'dockerfile',

        'make'=>'makefile'
    ];

    if($filename==='dockerfile'){
        return'dockerfile';
    }

    if($filename==='makefile'){
        return'makefile';
    }

    if(isset($languages[$extension])){
        return$languages[$extension];
    }

    $trimmed=ltrim($source);

    if($trimmed===''){
        return'plaintext';
    }

    if(
        str_starts_with($trimmed,'<?php')||
        preg_match('/<\?php\b/i',$trimmed)
    ){
        return'php';
    }

    if(
        preg_match('/^<!doctype\s+html/i',$trimmed)||
        preg_match('/^<html[\s>]/i',$trimmed)
    ){
        return'xml';
    }

    if(
        preg_match('/^\s*[\{\[]/',$trimmed)&&
        json_decode($source,true)!==null
    ){
        return'json';
    }

    if(
        preg_match(
            '/^\s*(from\s+\S+\s+import|import\s+\S+|def\s+\w+\s*\(|class\s+\w+.*:)/m',
            $source
        )||
        preg_match('/^\s*print\s*\(/m',$source)
    ){
        return'python';
    }

    if(
        preg_match(
            '/^\s*(const|let|var|function|import|export|class)\s+/m',
            $source
        )||
        str_contains($source,'console.log(')
    ){
        return'javascript';
    }

    if(
        preg_match(
            '/^\s*(SELECT|INSERT|UPDATE|DELETE|CREATE|ALTER|DROP)\s+/mi',
            $source
        )
    ){
        return'sql';
    }

    if(
        preg_match('/^\s*#include\s*[<"]/', $source)||
        preg_match('/\b(int|char|float|double|void)\s+main\s*\(/',$source)
    ){
        return'c';
    }

    return'plaintext';
}

$userAgent=$_SERVER['HTTP_USER_AGENT']??'Mozilla/5.0';

$licenseDirectory=$path;
$lastSlash=strrpos($licenseDirectory,'/');

if($lastSlash!==false){
    $lastPart=substr($licenseDirectory,$lastSlash+1);

    if(str_contains($lastPart,'.')&&!str_ends_with($lastPart,'.')){
        $licenseDirectory=substr($licenseDirectory,0,$lastSlash);
    }
}

$licenseDirectory=trim($licenseDirectory,'/');

$licenseSource='';
$licenseName='';
$licenseType='';

$readmeSource='';
$readmeFound=false;

$licenseNames=[
    'LICENSE',
    'license',
    'License',
    'LICENCE',
    'licence',
    'Licence',

    'LICENSE.txt',
    'license.txt',
    'License.txt',
    'LICENCE.txt',
    'licence.txt',
    'Licence.txt',

    'LICENSE.md',
    'license.md',
    'License.md',
    'LICENCE.md',
    'licence.md',
    'Licence.md',

    'LICENSE.markdown',
    'license.markdown',
    'License.markdown',

    'LICENSE.rst',
    'license.rst',
    'License.rst',

    'LICENSE.license',
    'license.license'
];

$readmeNames=[
    'README.md',
    'readme.md',
    'README.MD',
    'Readme.md',
    'README',
    'readme',
    'README.txt',
    'readme.txt'
];

foreach($licenseNames as $candidate){
    $licenseUrl=$scheme.'://'.$host.'/';

    if($licenseDirectory!==''){
        $licenseUrl.=$licenseDirectory.'/';
    }

    $licenseUrl.=$candidate;

    [$content,$licenseStatus]=fetchUrl($licenseUrl,$userAgent);

    if(
        $content!==false&&
        $licenseStatus>=200&&
        $licenseStatus<300&&
        trim((string)$content)!==''
    ){
        $licenseSource=(string)$content;
        $licenseName=$candidate;
        $licenseType=detectLicense($licenseSource,$licenseName);
        break;
    }
}

foreach($readmeNames as $candidate){
    $readmeUrl=$scheme.'://'.$host.'/';

    if($licenseDirectory!==''){
        $readmeUrl.=$licenseDirectory.'/';
    }

    $readmeUrl.=$candidate;

    [$content,$readmeStatus]=fetchUrl($readmeUrl,$userAgent);

    if(
        $content!==false&&
        $readmeStatus>=200&&
        $readmeStatus<300&&
        trim((string)$content)!==''
    ){
        $readmeSource=(string)$content;
        $readmeFound=true;
        break;
    }
}

if($licenseSource===''&&$licenseDirectory!==''){
    $directoryUrl=$scheme.'://'.$host.'/'.$licenseDirectory.'/';

    [$directoryContent,$directoryStatus]=fetchUrl(
        $directoryUrl,
        $userAgent
    );

    if(
        $directoryContent!==false&&
        $directoryStatus>=200&&
        $directoryStatus<300&&
        preg_match_all(
            '#(?:href|src)\s*=\s*["\']([^"\']+)["\']#i',
            (string)$directoryContent,
            $matches
        )
    ){
        foreach($matches[1] as $entry){
            $entry=urldecode($entry);
            $entry=basename(parse_url($entry,PHP_URL_PATH)??'');

            if($entry===''||str_contains($entry,'..')){
                continue;
            }

            $lower=strtolower($entry);

            if(
                $lower==='license'||
                $lower==='licence'||
                str_starts_with($lower,'license.')||
                str_starts_with($lower,'licence.')||
                str_starts_with($lower,'license-')||
                str_starts_with($lower,'licence-')||
                str_starts_with($lower,'license_')||
                str_starts_with($lower,'licence_')
            ){
                $licenseUrl=$directoryUrl.$entry;

                [$content,$licenseStatus]=fetchUrl(
                    $licenseUrl,
                    $userAgent
                );

                if(
                    $content!==false&&
                    $licenseStatus>=200&&
                    $licenseStatus<300&&
                    trim((string)$content)!==''
                ){
                    $licenseSource=(string)$content;
                    $licenseName=$entry;
                    $licenseType=detectLicense(
                        $licenseSource,
                        $licenseName
                    );
                    break;
                }
            }
        }
    }
}

[$source,$status]=fetchUrl($url,$userAgent);

if($source===false||$status>=400){
    http_response_code($status?:502);
    exit('Impossible de récupérer la page.');
}

$source=(string)$source;

/*
 * Détection du langage APRÈS récupération du fichier.
 */
$language=detectLanguage($path,$source);

$sourceJson=json_encode(
    $source,
    JSON_HEX_TAG|
    JSON_HEX_APOS|
    JSON_HEX_QUOT|
    JSON_HEX_AMP|
    JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES
);

if($sourceJson===false){
    http_response_code(500);
    exit('Erreur de traitement du contenu.');
}

$licenseJson=json_encode(
    $licenseSource,
    JSON_HEX_TAG|
    JSON_HEX_APOS|
    JSON_HEX_QUOT|
    JSON_HEX_AMP|
    JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES
);

if($licenseJson===false){
    $licenseJson='""';
}

$licenseTypeJson=json_encode(
    $licenseType,
    JSON_HEX_TAG|
    JSON_HEX_APOS|
    JSON_HEX_QUOT|
    JSON_HEX_AMP|
    JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES
);

if($licenseTypeJson===false){
    $licenseTypeJson='""';
}

$readmeJson=json_encode(
    $readmeSource,
    JSON_HEX_TAG|
    JSON_HEX_APOS|
    JSON_HEX_QUOT|
    JSON_HEX_AMP|
    JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES
);

if($readmeJson===false){
    $readmeJson='""';
}

$downloadName=basename($path);

if($downloadName===''){
    $downloadName='source.html';
}

$downloadNameJson=json_encode(
    $downloadName,
    JSON_HEX_TAG|
    JSON_HEX_APOS|
    JSON_HEX_QUOT|
    JSON_HEX_AMP|
    JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES
);

if($downloadNameJson===false){
    $downloadNameJson='"source.html"';
}

$hasLicense=$licenseSource!==''&&$licenseName!=='';

$githubUrl=$scheme.'://'.$host.'/';

if($licenseDirectory!==''){
    $githubUrl.=$licenseDirectory.'/';
}

$githubUrl.='githublink.txt';

[$githubContent,$githubStatus]=fetchUrl(
    $githubUrl,
    $userAgent
);

$githubLink='';

if(
    $githubContent!==false&&
    $githubStatus>=200&&
    $githubStatus<300
){
    $candidateGithub=trim((string)$githubContent);

    if(filter_var($candidateGithub,FILTER_VALIDATE_URL)){
        $githubLink=$candidateGithub;
    }
}

$languageJson=json_encode(
    $language,
    JSON_HEX_TAG|
    JSON_HEX_APOS|
    JSON_HEX_QUOT|
    JSON_HEX_AMP|
    JSON_UNESCAPED_UNICODE|
    JSON_UNESCAPED_SLASHES
);

if($languageJson===false){
    $languageJson='"plaintext"';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<title><?=htmlspecialchars('/'.$path,ENT_QUOTES,'UTF-8')?></title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/styles/github.min.css">

<style>
*{box-sizing:border-box}
html,body{margin:0;width:100%;height:100%;background:#fff}
body{overflow:hidden;font-family:Arial,Helvetica,sans-serif}

.editor{position:absolute;top:0;left:0;right:0;bottom:30px;display:flex;overflow:auto;min-width:0;min-height:0}

.line-numbers{
    flex:none;
    width:52px;
    min-height:100%;
    padding:0 12px 0 0;
    background:#fff;
    border-right:1px solid #e5e7eb;
    color:#858585;
    text-align:right;
    font:13px/20px Consolas,"Courier New",monospace;
    user-select:none
}

.line-numbers div{height:20px}

.code-area{
    flex:none;
    min-width:calc(100vw - 52px);
    min-height:100%;
    width:max-content;
    overflow:visible
}

pre{
    display:block;
    width:max-content;
    min-width:100%;
    margin:0;
    padding:0 14px;
    background:#fff;
    color:#24292f;
    font:13px/20px Consolas,"Courier New",monospace;
    tab-size:4;
    white-space:pre
}

code{font-family:inherit}
.hljs{background:#fff!important;padding:0!important}

.statusbar{
    position:fixed;
    z-index:100;
    left:0;
    right:0;
    bottom:0;
    width:100%;
    height:30px;
    display:flex;
    align-items:center;
    gap:20px;
    padding:0 8px 0 12px;
    background:#f3f3f3;
    border-top:1px solid #d4d4d4;
    color:#555;
    font:12px Consolas,"Courier New",monospace;
    box-shadow:0 -2px 5px rgba(0,0,0,.08)
}

.status-item{white-space:nowrap}
.status-label{color:#777}
.status-value{color:#333;font-weight:600}
.status-actions{margin-left:auto;display:flex;align-items:center;gap:2px}

.license-button,
.readme-button,
.github-button,
.source-zip-button{
    flex:none;
    width:28px;
    height:28px;
    padding:0;
    border:0;
    outline:0;
    background:transparent;
    color:#555;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    border-radius:4px
}

.license-button:hover,
.readme-button:hover,
.github-button:hover,
.source-zip-button:hover{
    background:#e2e2e2;
    color:#222
}

.license-button:active,
.readme-button:active,
.github-button:active,
.source-zip-button:active{
    background:#d5d5d5
}

.license-button svg,
.readme-button svg,
.github-button svg,
.source-zip-button svg{
    display:block;
    width:20px;
    height:20px
}

.github-button{text-decoration:none}

.overlay{
    position:fixed;
    z-index:1000;
    inset:0;
    display:none;
    align-items:center;
    justify-content:center;
    padding:30px;
    background:rgba(0,0,0,.35)
}

.overlay.open{display:flex}

.window{
    width:min(900px,100%);
    height:min(700px,90vh);
    min-width:0;
    display:flex;
    flex-direction:column;
    background:#fff;
    border:1px solid #cfcfcf;
    border-radius:6px;
    box-shadow:0 12px 40px rgba(0,0,0,.25);
    overflow:hidden
}

.header{
    flex:none;
    height:42px;
    display:flex;
    align-items:center;
    padding:0 8px 0 14px;
    background:#f3f3f3;
    border-bottom:1px solid #d8d8d8;
    font:13px Arial,Helvetica,sans-serif;
    color:#333
}

.title{
    flex:1;
    overflow:hidden;
    white-space:nowrap;
    text-overflow:ellipsis
}

.actions{display:flex;align-items:center;gap:2px}

.close-btn,
.info-btn,
.download-btn{
    flex:none;
    width:28px;
    height:28px;
    padding:0;
    border:0;
    outline:0;
    background:transparent;
    color:#555;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    border-radius:4px
}

.close-btn{font-size:22px;line-height:28px}

.info-btn svg,
.download-btn svg{
    width:19px;
    height:19px
}

.close-btn:hover,
.info-btn:hover,
.download-btn:hover{
    background:#ddd;
    color:#111
}

.content{
    flex:1;
    min-width:0;
    width:100%;
    max-width:100%;
    overflow:auto;
    margin:0;
    padding:16px;
    background:#fff;
    color:#24292f;
    white-space:pre-wrap!important;
    overflow-wrap:anywhere!important;
    word-break:break-word!important;
    font:13px/20px Consolas,"Courier New",monospace
}

.license-info{
    display:none;
    flex:1;
    min-width:0;
    width:100%;
    overflow:auto;
    padding:20px;
    background:#fff;
    color:#24292f;
    font:13px/20px Arial,Helvetica,sans-serif
}

.license-info.open{display:block}
.content.hidden{display:none}

.license-info h2{margin:0 0 18px;font-size:18px;font-weight:600}
.license-info h3{margin:18px 0 7px;font-size:14px;font-weight:600}
.license-info p{margin:7px 0}
.license-info ul{margin:7px 0 14px;padding-left:22px}
.license-info li{margin:4px 0}

.license-note{
    margin-top:20px;
    padding:10px 12px;
    border:1px solid #ddd;
    background:#f6f6f6;
    border-radius:4px;
    color:#666;
    font-size:12px;
    line-height:18px
}

.readme-content{
    flex:1;
    min-width:0;
    width:100%;
    max-width:100%;
    overflow:auto;
    margin:0;
    padding:20px;
    background:#fff;
    color:#24292f;
    font:15px/1.6 -apple-system,BlinkMacSystemFont,"Segoe UI",Helvetica,Arial,sans-serif
}

.readme-content *{max-width:100%}
.readme-content h1{margin:20px 0 16px;font-size:32px;font-weight:700;border-bottom:2px solid #e5e7eb;padding-bottom:12px}
.readme-content h2{margin:16px 0 12px;font-size:24px;font-weight:700}
.readme-content h3{margin:12px 0 8px;font-size:20px;font-weight:600}
.readme-content h4{margin:10px 0 6px;font-size:16px;font-weight:600}
.readme-content h5{margin:8px 0 4px;font-size:14px;font-weight:600}
.readme-content h6{margin:8px 0 4px;font-size:13px;font-weight:600;color:#666}
.readme-content p{margin:12px 0}
.readme-content ul{margin:8px 0;padding-left:24px}
.readme-content ol{margin:8px 0;padding-left:24px}
.readme-content li{margin:4px 0;line-height:1.8}
.readme-content li>ul,.readme-content li>ol{margin:4px 0 4px 16px}
.readme-content code{background:#f3f3f3;padding:2px 6px;border-radius:3px;font-family:Consolas,"Courier New",monospace;font-size:13px;color:#d73a49}
.readme-content pre{background:#f3f3f3;padding:16px;border-radius:4px;overflow-x:auto;margin:12px 0;border:1px solid #e5e7eb}
.readme-content pre code{background:none;padding:0;color:inherit;font-size:13px}
.readme-content blockquote{margin:12px 0;padding:0 12px;border-left:4px solid #d1d5da;background:#f9f9f9;color:#666}
.readme-content strong{font-weight:600;color:#24292f}
.readme-content em{font-style:italic}
.readme-content del{color:#d73a49;text-decoration:line-through}
.readme-content a{color:#0969da;text-decoration:none;border-bottom:1px solid #0969da}
.readme-content a:hover{color:#0860ca}
.readme-content img{max-width:100%;height:auto;margin:12px 0;border-radius:4px;box-shadow:0 1px 4px rgba(0,0,0,.1)}
.readme-content hr{margin:16px 0;border:none;border-top:2px solid #e5e7eb;height:0}
.readme-content table{border-collapse:collapse;width:100%;margin:12px 0;border:1px solid #d0d7de}
.readme-content table td,
.readme-content table th{border:1px solid #d0d7de;padding:12px 16px;text-align:left}
.readme-content table th{background:#f6f8fa;font-weight:600}
.readme-content table tr:nth-child(2n){background:#f9f9f9}
.readme-content .task-list{list-style:none;padding-left:0}
.readme-content .task-list li{margin:8px 0;list-style:none;display:flex;align-items:flex-start;gap:8px}
.readme-content .task-list input{margin:3px 0 0 0;cursor:pointer}
.readme-content .task-list input[disabled]{cursor:default;opacity:.5}

.findbar{
    position:fixed;
    z-index:2000;
    top:10px;
    right:18px;
    width:360px;
    height:40px;
    display:none;
    align-items:center;
    padding:5px 7px;
    background:#fff;
    border:1px solid #cfcfcf;
    border-radius:5px;
    box-shadow:0 3px 12px rgba(0,0,0,.18);
    font:13px Arial,Helvetica,sans-serif
}

.findbar.open{display:flex}

.find-input{
    flex:1;
    min-width:0;
    padding:0 16px;
    box-sizing:border-box;
    border:0;
    border-radius:8px 0 0 8px;
    outline:none;
    background:#fff;
    color:#202124;
    font:14px Arial,Helvetica,sans-serif;
    box-shadow:none
}

.find-input:hover,
.find-input:focus{
    border:0;
    outline:none;
    box-shadow:none;
    background:#fff
}

.find-input::placeholder{color:#777}

.find-count{
    flex:none;
    min-width:48px;
    text-align:center;
    color:#666;
    font:12px Consolas,"Courier New",monospace
}

.find-button{
    flex:none;
    width:28px;
    height:28px;
    padding:0;
    border:0;
    background:transparent;
    color:#555;
    border-radius:3px;
    cursor:pointer;
    font-size:15px
}

.find-button:hover{background:#eee}
.find-close{font-size:19px}

.search-match{background:#ffe169!important;color:#111!important;border-radius:2px}
.search-current{background:#ff9f1c!important;color:#111!important}

::-webkit-scrollbar{width:10px;height:10px}
::-webkit-scrollbar-track{background:#f1f1f1}
::-webkit-scrollbar-thumb{background:#c5c5c5;border-radius:5px}
::-webkit-scrollbar-thumb:hover{background:#a8a8a8}

.license-list{
    list-style:none!important;
    margin:7px 0 14px!important;
    padding:0!important
}

.license-list li{
    display:flex;
    align-items:flex-start;
    gap:8px;
    margin:7px 0;
    list-style:none!important
}

.license-status-icon{
    flex:none;
    width:18px;
    height:18px;
    margin-top:1px
}

.license-status-ok{color:#198754}
.license-status-no{color:#dc3545}
.license-status-warning{color:#f08c00}

.license-list li span{
    flex:1;
    min-width:0
}
</style>
</head>

<body>

<div class="editor">
    <div class="line-numbers" id="lineNumbers"></div>

    <div class="code-area">
        <pre><code id="code" class="language-<?=htmlspecialchars($language,ENT_QUOTES,'UTF-8')?>"></code></pre>
    </div>
</div>

<div class="statusbar">

    <div class="status-item">
        <span class="status-label">Caractères :</span>
        <span class="status-value" id="charCount">0</span>
    </div>

    <div class="status-item">
        <span class="status-label">Taille :</span>
        <span class="status-value" id="size">0 o</span>
    </div>

    <div class="status-item">
        <span class="status-label">Langage :</span>
        <span class="status-value" id="languageName"><?=htmlspecialchars($language,ENT_QUOTES,'UTF-8')?></span>
    </div>

    <div class="status-actions">

        <?php if($githubLink!==''): ?>

        <a
            class="github-button"
            href="<?=htmlspecialchars($githubLink,ENT_QUOTES,'UTF-8')?>"
            target="_blank"
            rel="noopener noreferrer"
            title="Ouvrir le dépôt GitHub"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.52 2.34 1.07 2.91.83c.09-.65.35-1.09.63-1.34c-2.22-.25-4.55-1.11-4.55-4.92c0-1.11.38-2 1.03-2.71c-.1-.25-.45-1.29.1-2.64c0 0 .84-.27 2.75 1.02c.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02c.55 1.35.2 2.39.1 2.64c.65.71 1.03 1.6 1.03 2.71c0 3.82-2.34 4.66-4.57 4.91c.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2"/>
            </svg>
        </a>

        <?php endif; ?>

        <button
            class="source-zip-button"
            id="sourceZipButton"
            title="Télécharger les sources en ZIP"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825-.1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20zm10-2h2v-2h2v-2h-2v-2h2v-2h-2V8h-2v2h2v2h-2v2h2v2h-2z"/>
            </svg>
        </button>

        <?php if($readmeFound): ?>

        <button
            class="readme-button"
            id="readmeButton"
            title="Afficher le README"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M7.5 22q-1.45 0-2.475-1.025T4 18.5v-13q0-1.45 1.025-2.475T7.5 2H20v15q-.625 0-1.062.438T18.5 18.5t.438 1.063T20 20v2zm.5-7h2V4H8zm-.5 5h9.325q-.15-.35-.237-.712T16.5 18.5q0-.4.075-.775t.25-.725H7.5q-.65 0-1.075.438T6 18.5q0 .65.425 1.075T7.5 20"/>
            </svg>
        </button>

        <?php endif; ?>

        <?php if($hasLicense): ?>

        <button
            class="license-button"
            id="licenseButton"
            title="Afficher la licence"
        >
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M9.875 12.125Q9 11.25 9 10t.875-2.125T12 7t2.125.875T15 10t-.875 2.125T12 13t-2.125-.875M6 23v-7.725q-.95-1.05-1.475-2.4T4 10q0-3.35 2.325-5.675T12 2t5.675 2.325T20 10q0 1.525-.525 2.875T18 15.275V23l-6-2zm10.25-8.75Q18 12.5 18 10t-1.75-4.25T12 4T7.75 5.75T6 10t1.75 4.25T12 16t4.25-1.75"/>
            </svg>
        </button>

        <?php endif; ?>

    </div>
</div>

<div class="findbar" id="findbar">

    <input
        class="find-input"
        id="findInput"
        type="text"
        placeholder="Rechercher dans le code"
        autocomplete="off"
        spellcheck="false"
    >

    <span class="find-count" id="findCount"></span>

    <button class="find-button" id="findPrev" title="Précédent">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6.343 14.657L12 9l5.657 5.657"/>
        </svg>
    </button>

    <button class="find-button" id="findNext" title="Suivant">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 9.343L12 15L6.343 9.343"/>
        </svg>
    </button>

    <button class="find-button find-close" id="findClose" title="Fermer">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path fill="currentColor" fill-rule="evenodd" d="M6.793 6.793a1 1 0 0 1 1.414 0L12 10.586l3.793-3.793a1 1 0 1 1 1.414 1.414L13.414 12l3.793 3.793a1 1 0 0 1-1.414 1.414L12 13.414l-3.793 3.793a1 1 0 0 1-1.414-1.414L10.586 12L6.793 8.207a1 1 0 0 1 0-1.414"/>
        </svg>
    </button>

</div>

<?php if($readmeFound): ?>

<div class="overlay" id="readmeOverlay">
    <div class="window" role="dialog" aria-modal="true">

        <div class="header">
            <div class="title">README</div>

            <div class="actions">
                <button class="close-btn" id="readmeClose">×</button>
            </div>
        </div>

        <div class="readme-content" id="readmeContent"></div>

    </div>
</div>

<?php endif; ?>

<?php if($hasLicense): ?>

<div class="overlay" id="licenseOverlay">

    <div class="window" role="dialog" aria-modal="true">

        <div class="header">

            <div class="title" id="licenseTitle">
                Licence — <?=htmlspecialchars($licenseType,ENT_QUOTES,'UTF-8')?>
            </div>

            <div class="actions">

                <button
                    class="info-btn"
                    id="licenseInfo"
                    title="Informations sur la licence"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path stroke-linecap="round" d="M12 7h.01"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 11h2v5m-2 0h4"/>
                        </g>
                    </svg>
                </button>

                <button
                    class="download-btn"
                    id="licenseDownload"
                    title="Télécharger la licence"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M11.625 15.513q-.175-.063-.325-.213l-3.6-3.6q-.3-.3-.288-.7t.288-.7q.3-.3.713-.312t.712.287L11 12.15V5q0-.425.288-.712T12 4t.713.288T13 5v7.15l1.875-1.875q.3-.3.713-.288t.712.313q.275.3.288.7t-.288.7l-3.6 3.6q-.15.15-.325.213t-.375.062M6 20q-.825 0-1.412-.587T4 18v-2q0-.425.288-.712T5 15t.713.288T6 16v2h12v-2q0-.425.288-.712T19 15t.713.288T20 16v2q0 .825-.587 1.413T18 20z"/>
                    </svg>
                </button>

                <button class="close-btn" id="licenseClose">×</button>

            </div>
        </div>

        <pre class="content" id="licenseContent"></pre>

        <div class="license-info" id="licenseInfoContent"></div>

    </div>
</div>

<?php endif; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.11.1/highlight.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>

<script type="module">
import {marked} from 'https://cdn.jsdelivr.net/npm/marked@11.1.1/+esm';

const source=<?=$sourceJson?>;
const downloadName=<?=$downloadNameJson?>;
const licenseSource=<?=$licenseJson?>;
const readmeSource=<?=$readmeJson?>;
const detectedLanguage=<?=$languageJson?>;

const code=document.getElementById('code');
const lineNumbers=document.getElementById('lineNumbers');
const charCount=document.getElementById('charCount');
const size=document.getElementById('size');
const languageName=document.getElementById('languageName');

function beautifyHTML(html){
    html=html
        .replace(/\r\n/g,'\n')
        .replace(/\r/g,'\n')
        .trim();

    const tokens=html.match(/<!--[\s\S]*?-->|<[^>]+>|[^<]+/g)||[];

    const voidTags=new Set([
        'area','base','br','col','embed','hr',
        'img','input','link','meta','param',
        'source','track','wbr'
    ]);

    const inlineTags=new Set([
        'a','abbr','b','bdi','bdo','button',
        'cite','code','data','del','em','i',
        'img','input','ins','kbd','label',
        'mark','q','s','samp','small','span',
        'strong','sub','sup','time','u','var'
    ]);

    let output=[];
    let indent=0;
    let i=0;

    function name(t){
        const m=t.match(/^<\s*\/?\s*([a-zA-Z0-9:-]+)/);
        return m?m[1].toLowerCase():'';
    }

    function add(t,n){
        t=t.trim();

        if(t){
            output.push(' '.repeat(Math.max(0,n))+t);
        }
    }

    while(i<tokens.length){

        const t=tokens[i];

        if(t.startsWith('<!--')){
            add(t,indent);
            i++;
            continue;
        }

        if(t.startsWith('</')){
            indent=Math.max(0,indent-1);
            add(t,indent);
            i++;
            continue;
        }

        if(t.startsWith('<')){

            const n=name(t);
            const self=/\/\s*>$/.test(t);
            const voidTag=voidTags.has(n);

            if(
                !self&&
                !voidTag&&
                inlineTags.has(n)&&
                tokens[i+1]&&
                !tokens[i+1].startsWith('<')&&
                tokens[i+2]&&
                /^<\s*\/\s*/.test(tokens[i+2])&&
                name(tokens[i+2])===n
            ){
                add(
                    t.trim()+
                    tokens[i+1].trim()+
                    tokens[i+2].trim(),
                    indent
                );

                i+=3;
                continue;
            }

            add(t,indent);

            if(
                !self&&
                !voidTag&&
                !/^<!/.test(t)&&
                !/^<\?/.test(t)
            ){
                indent++;
            }

            i++;
            continue;
        }

        const text=t.trim();

        if(text){
            add(text,indent);
        }

        i++;
    }

    return output.join('\n');
}

function prepareSource(v){

    v=v
        .replace(/\r\n/g,'\n')
        .replace(/\r/g,'\n');

    if(v.split('\n').length>1){
        return v;
    }

    if(
        /<(!DOCTYPE|html|head|body|div|main|section|header|footer|style|script|link|meta)/i
        .test(v)
    ){
        return beautifyHTML(v);
    }

    return v;
}

function formatBytes(bytes){

    if(bytes<1024){
        return bytes+' o';
    }

    if(bytes<1048576){
        return(
            (bytes/1024)
            .toFixed(bytes<10240?1:0)
            .replace('.',',')
            +' Ko'
        );
    }

    return(
        (bytes/1048576)
        .toFixed(2)
        .replace('.',',')
        +' Mo'
    );
}

const formatted=prepareSource(source);

code.textContent=formatted;

const count=formatted?formatted.split('\n').length:1;

let numbers='';

for(let i=1;i<=count;i++){
    numbers+='<div>'+i+'</div>';
}

lineNumbers.innerHTML=numbers;

charCount.textContent=
    formatted.length.toLocaleString('fr-FR');

size.textContent=
    formatBytes(new Blob([formatted]).size);

if(languageName){
    languageName.textContent=detectedLanguage;
}

if(window.hljs){

    /*
     * Le langage a déjà été détecté côté PHP.
     * On conserve la classe language-* sur <code>.
     */
    code.className='language-'+detectedLanguage;

    hljs.highlightElement(code);
}

function downloadSource(){

    const blob=new Blob(
        [formatted],
        {type:'text/plain;charset=utf-8'}
    );

    const url=URL.createObjectURL(blob);

    const a=document.createElement('a');

    a.href=url;
    a.download=downloadName;

    document.body.appendChild(a);

    a.click();

    a.remove();

    setTimeout(function(){
        URL.revokeObjectURL(url);
    },1000);
}

async function downloadSourceZip(){

    if(typeof JSZip==='undefined'){
        alert('Impossible de charger le module ZIP.');
        return;
    }

    const zip=new JSZip();

    zip.file(downloadName,formatted);

    if(
        licenseSource&&
        licenseSource.trim()!==''
    ){
        zip.file('LICENSE',licenseSource);
    }

    const blob=await zip.generateAsync({
        type:'blob',
        compression:'DEFLATE',
        compressionOptions:{level:9}
    });

    const url=URL.createObjectURL(blob);

    const a=document.createElement('a');

    a.href=url;
    a.download='source.zip';

    document.body.appendChild(a);

    a.click();

    a.remove();

    setTimeout(function(){
        URL.revokeObjectURL(url);
    },1000);
}

function downloadLicense(){

    const blob=new Blob(
        [licenseSource],
        {type:'text/plain;charset=utf-8'}
    );

    const url=URL.createObjectURL(blob);

    const a=document.createElement('a');

    a.href=url;

    a.download=<?=json_encode(
        $licenseName!==''?$licenseName:'LICENSE.txt',
        JSON_HEX_TAG|
        JSON_HEX_APOS|
        JSON_HEX_QUOT|
        JSON_HEX_AMP|
        JSON_UNESCAPED_UNICODE|
        JSON_UNESCAPED_SLASHES
    )?>;

    document.body.appendChild(a);

    a.click();

    a.remove();

    setTimeout(function(){
        URL.revokeObjectURL(url);
    },1000);
}

const findbar=document.getElementById('findbar');
const findInput=document.getElementById('findInput');
const findCount=document.getElementById('findCount');
const findPrev=document.getElementById('findPrev');
const findNext=document.getElementById('findNext');
const findClose=document.getElementById('findClose');

let searchMatches=[];
let currentMatch=-1;

function clearSearch(){

    document.querySelectorAll('.search-match')
        .forEach(el=>{

            const parent=el.parentNode;

            if(!parent){
                return;
            }

            while(el.firstChild){
                parent.insertBefore(
                    el.firstChild,
                    el
                );
            }

            parent.removeChild(el);
            parent.normalize();
        });

    searchMatches=[];
    currentMatch=-1;

    if(findCount){
        findCount.textContent='';
    }
}

function searchCode(){

    clearSearch();

    if(!findInput){
        return;
    }

    const query=findInput.value;

    if(!query){
        return;
    }

    const lower=query.toLocaleLowerCase();

    const walker=document.createTreeWalker(
        code,
        NodeFilter.SHOW_TEXT
    );

    const nodes=[];

    let node;

    while(node=walker.nextNode()){
        nodes.push(node);
    }

    nodes.forEach(textNode=>{

        const text=textNode.nodeValue||'';
        const textLower=text.toLocaleLowerCase();

        let pos=0;
        const found=[];

        while(
            (pos=textLower.indexOf(lower,pos))!==-1
        ){
            found.push([
                pos,
                pos+query.length
            ]);

            pos+=query.length||1;
        }

        if(!found.length){
            return;
        }

        const fragment=document.createDocumentFragment();

        let last=0;

        found.forEach(([start,end])=>{

            if(start>last){
                fragment.appendChild(
                    document.createTextNode(
                        text.slice(last,start)
                    )
                );
            }

            const mark=document.createElement('mark');

            mark.className='search-match';
            mark.textContent=text.slice(start,end);

            fragment.appendChild(mark);

            last=end;
        });

        if(last<text.length){
            fragment.appendChild(
                document.createTextNode(
                    text.slice(last)
                )
            );
        }

        if(textNode.parentNode){
            textNode.parentNode.replaceChild(
                fragment,
                textNode
            );
        }
    });

    searchMatches=[
        ...code.querySelectorAll('.search-match')
    ];

    if(searchMatches.length){

        currentMatch=0;

        searchMatches[0]
            .classList
            .add('search-current');

        searchMatches[0].scrollIntoView({
            block:'center',
            inline:'nearest'
        });

        if(findCount){
            findCount.textContent=
                '1/'+searchMatches.length;
        }

    }else if(findCount){

        findCount.textContent='0/0';
    }
}

function selectMatch(index){

    if(!searchMatches.length){
        return;
    }

    searchMatches.forEach(el=>{
        el.classList.remove('search-current');
    });

    currentMatch=
        (index+searchMatches.length)%
        searchMatches.length;

    const match=searchMatches[currentMatch];

    if(!match){
        return;
    }

    match.classList.add('search-current');

    match.scrollIntoView({
        block:'center',
        inline:'nearest'
    });

    if(findCount){
        findCount.textContent=
            (currentMatch+1)+
            '/'+
            searchMatches.length;
    }
}

function nextMatch(){
    selectMatch(currentMatch+1);
}

function previousMatch(){
    selectMatch(currentMatch-1);
}

function openFind(){

    if(!findbar||!findInput){
        return;
    }

    findbar.classList.add('open');

    findInput.focus();
    findInput.select();
}

function closeFind(){

    if(findbar){
        findbar.classList.remove('open');
    }

    clearSearch();
}

if(findInput){

    findInput.addEventListener(
        'input',
        searchCode
    );

    findInput.addEventListener(
        'keydown',
        function(e){

            if(e.key==='Enter'){

                e.preventDefault();

                if(e.shiftKey){
                    previousMatch();
                }else{
                    nextMatch();
                }
            }

            if(e.key==='Escape'){

                e.preventDefault();

                closeFind();
            }
        }
    );
}

if(findNext){
    findNext.addEventListener(
        'click',
        nextMatch
    );
}

if(findPrev){
    findPrev.addEventListener(
        'click',
        previousMatch
    );
}

if(findClose){
    findClose.addEventListener(
        'click',
        closeFind
    );
}

const sourceZipButton=
    document.getElementById('sourceZipButton');

if(sourceZipButton){
    sourceZipButton.addEventListener(
        'click',
        downloadSourceZip
    );
}

document.addEventListener(
    'keydown',
    function(e){

        if(
            (e.ctrlKey||e.metaKey)&&
            e.key.toLowerCase()==='s'
        ){
            e.preventDefault();

            downloadSource();

            return;
        }

        if(
            (e.ctrlKey||e.metaKey)&&
            e.key.toLowerCase()==='f'
        ){
            e.preventDefault();

            openFind();

            return;
        }

        if(
            e.key==='Escape'&&
            findbar&&
            findbar.classList.contains('open')
        ){
            closeFind();

            return;
        }

        if(
            (e.ctrlKey||e.metaKey)&&
            e.key.toLowerCase()==='a'&&
            document.activeElement!==findInput
        ){
            e.preventDefault();

            const selection=window.getSelection();
            const range=document.createRange();

            range.selectNodeContents(code);

            selection.removeAllRanges();
            selection.addRange(range);
        }
    }
);

const readmeButton=
    document.getElementById('readmeButton');

if(readmeButton){

    const readmeOverlay=
        document.getElementById('readmeOverlay');

    const readmeClose=
        document.getElementById('readmeClose');

    const readmeContent=
        document.getElementById('readmeContent');

    if(
        readmeOverlay&&
        readmeClose&&
        readmeContent
    ){

        marked.setOptions({
            breaks:true,
            gfm:true
        });

        const html=marked.parse(readmeSource);

        const clean=DOMPurify.sanitize(html);

        readmeContent.innerHTML=clean;

        readmeContent
            .querySelectorAll('pre code')
            .forEach(block=>{

                if(window.hljs){
                    hljs.highlightElement(block);
                }
            });

        readmeButton.addEventListener(
            'click',
            function(){

                readmeOverlay.classList.add('open');

                readmeClose.focus();
            }
        );

        readmeClose.addEventListener(
            'click',
            function(){

                readmeOverlay.classList.remove('open');
            }
        );

        readmeOverlay.addEventListener(
            'click',
            function(e){

                if(e.target===readmeOverlay){
                    readmeOverlay.classList.remove('open');
                }
            }
        );

        document.addEventListener(
            'keydown',
            function(e){

                if(
                    e.key==='Escape'&&
                    readmeOverlay.classList.contains('open')
                ){
                    readmeOverlay.classList.remove('open');
                }
            }
        );
    }
}

<?php if($hasLicense): ?>

const licenseButton=
    document.getElementById('licenseButton');

const licenseOverlay=
    document.getElementById('licenseOverlay');

const licenseClose=
    document.getElementById('licenseClose');

const licenseInfo=
    document.getElementById('licenseInfo');

const licenseContent=
    document.getElementById('licenseContent');

const licenseInfoContent=
    document.getElementById('licenseInfoContent');

const licenseDownload=
    document.getElementById('licenseDownload');

const licenseType=<?=$licenseTypeJson?>;

let licenseInfoMode=false;

if(licenseContent){
    licenseContent.textContent=licenseSource;
}

const licenseInformation={

    'Survivalier License':{

        title:'Survivalier License v1.0',

        allowed:[
            'Utiliser le projet à titre personnel',
            'Utiliser le projet à titre privé',
            'Utiliser le projet à des fins non commerciales',
            'Accéder et inspecter le code source',
            'Copier le projet pour les usages autorisés',
            'Modifier le projet'
        ],

        required:[
            'Conserver les notices de copyright et de propriété intellectuelle',
            'Conserver une copie complète de la Survivalier License',
            'Les versions modifiées distribuées doivent rester sous la même licence',
            'Indiquer clairement les modifications apportées',
            'Conserver les informations d’attribution et de contributeurs',
            'Créditer les contributeurs lorsque cela est applicable'
        ],

        forbidden:[
            'Utiliser le projet à des fins commerciales sans autorisation écrite préalable',
            'Retirer ou remplacer la licence lors de la distribution d’une version dérivée',
            'Présenter une version modifiée comme la version originale non modifiée',
            'Supprimer les notices de copyright ou d’attribution requises'
        ]
    },

    'MIT':{

        title:'MIT',

        allowed:[
            'Utiliser le code à titre personnel ou commercial',
            'Copier et modifier le code',
            'Distribuer le code ou une version modifiée',
            'Inclure le code dans un autre projet, y compris propriétaire'
        ],

        required:[
            'Conserver la notice de copyright',
            'Conserver le texte de la licence lors de la redistribution'
        ],

        forbidden:[
            'La licence n’interdit pas spécifiquement la vente du logiciel',
            'Aucune obligation de publier le code source modifié'
        ]
    },

    'GNU GPL':{

        title:'GNU GPL',

        allowed:[
            'Utiliser le logiciel pour n’importe quel usage',
            'Modifier le code source',
            'Redistribuer le logiciel',
            'Distribuer des versions modifiées'
        ],

        required:[
            'Conserver les notices de copyright et de licence',
            'Fournir le code source correspondant lorsque les conditions de la GPL l’exigent',
            'Les versions dérivées couvertes par la GPL doivent respecter les conditions de la GPL'
        ],

        forbidden:[
            'Distribuer une version couverte par la GPL sous des conditions incompatibles',
            'Retirer les libertés garanties par la GPL aux destinataires'
        ]
    },

    'GNU LGPL':{

        title:'GNU LGPL',

        allowed:[
            'Utiliser la bibliothèque dans des projets libres ou propriétaires selon les conditions de la LGPL',
            'Modifier la bibliothèque',
            'Redistribuer la bibliothèque'
        ],

        required:[
            'Conserver les notices et la licence',
            'Respecter les obligations de la LGPL lors de la modification ou redistribution'
        ],

        forbidden:[
            'Empêcher les utilisateurs de bénéficier des droits accordés par la LGPL'
        ]
    },

    'GNU AGPL':{

        title:'GNU AGPL',

        allowed:[
            'Utiliser le logiciel',
            'Modifier le logiciel',
            'Redistribuer le logiciel'
        ],

        required:[
            'Respecter les obligations de la GPL',
            'Pour certains logiciels modifiés utilisés via un réseau, proposer le code source correspondant aux utilisateurs concernés'
        ],

        forbidden:[
            'Distribuer une version dérivée sous des conditions qui retirent les libertés de l’AGPL'
        ]
    },

    'Apache 2.0':{

        title:'Apache 2.0',

        allowed:[
            'Utiliser le code à titre personnel ou commercial',
            'Modifier le code',
            'Distribuer le code',
            'Distribuer des versions modifiées',
            'Utiliser les droits de brevet accordés par la licence'
        ],

        required:[
            'Conserver les notices de copyright et la licence',
            'Indiquer les modifications importantes apportées aux fichiers',
            'Conserver les notices de licence et de propriété intellectuelle requises'
        ],

        forbidden:[
            'Utiliser les marques ou noms des auteurs comme si leur utilisation était autorisée',
            'Prétendre que les auteurs garantissent le logiciel'
        ]
    },

    'BSD 3-Clause':{

        title:'BSD 3-Clause',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Utiliser le code commercialement',
            'Redistribuer le code source ou binaire'
        ],

        required:[
            'Conserver les notices de copyright et les conditions de licence lors des redistributions'
        ],

        forbidden:[
            'Utiliser le nom des auteurs ou contributeurs pour promouvoir un produit sans autorisation'
        ]
    },

    'BSD 2-Clause':{

        title:'BSD 2-Clause',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Utiliser le code commercialement',
            'Redistribuer le code source ou binaire'
        ],

        required:[
            'Conserver les notices de copyright et les conditions de licence'
        ],

        forbidden:[
            'Supprimer les notices requises lors de la redistribution'
        ]
    },

    'BSD':{

        title:'BSD',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Redistribuer le code',
            'Utiliser le code commercialement'
        ],

        required:[
            'Conserver les notices et conditions applicables'
        ],

        forbidden:[
            'Utiliser les noms des auteurs pour promouvoir un produit lorsque la licence l’interdit'
        ]
    },

    'ISC':{

        title:'ISC',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Redistribuer le code',
            'Utiliser le code commercialement'
        ],

        required:[
            'Conserver la notice de copyright et la licence lors des redistributions'
        ],

        forbidden:[
            'La licence impose très peu de restrictions supplémentaires'
        ]
    },

    'MPL 2.0':{

        title:'Mozilla Public License 2.0',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Distribuer le code',
            'Inclure le code dans un projet plus large, y compris propriétaire'
        ],

        required:[
            'Les fichiers modifiés couverts par la MPL doivent généralement rester sous MPL lors de leur distribution',
            'Conserver les notices et le texte de licence'
        ],

        forbidden:[
            'Supprimer les obligations de la MPL pour les fichiers qu’elle couvre'
        ]
    },

    'EPL 2.0':{

        title:'Eclipse Public License 2.0',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Distribuer le code',
            'Utiliser le code dans des projets commerciaux'
        ],

        required:[
            'Respecter les conditions de l’EPL lors de la redistribution',
            'Conserver les notices et licences applicables'
        ],

        forbidden:[
            'Distribuer les composants couverts en supprimant les droits accordés par l’EPL'
        ]
    },

    'Unlicense':{

        title:'Unlicense',

        allowed:[
            'Utiliser le code',
            'Copier le code',
            'Modifier le code',
            'Distribuer le code',
            'Utiliser le code commercialement'
        ],

        required:[
            'Aucune obligation majeure supplémentaire selon les termes de l’Unlicense'
        ],

        forbidden:[
            'La licence cherche à placer le code dans le domaine public dans la mesure permise par la loi'
        ]
    },

    'zlib':{

        title:'zlib',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Utiliser le code commercialement',
            'Redistribuer le code source ou binaire'
        ],

        required:[
            'Ne pas prétendre être l’auteur du code original',
            'Respecter les conditions de redistribution'
        ],

        forbidden:[
            'Présenter le code original comme étant votre propre travail'
        ]
    },

    'Boost':{

        title:'Boost Software License',

        allowed:[
            'Utiliser le code',
            'Modifier le code',
            'Redistribuer le code',
            'Utiliser le code commercialement'
        ],

        required:[
            'Respecter les conditions de redistribution de la licence'
        ],

        forbidden:[
            'Supprimer les conditions applicables lors de la redistribution'
        ]
    },

    'WTFPL':{

        title:'WTFPL',

        allowed:[
            'Utiliser le code',
            'Copier le code',
            'Modifier le code',
            'Redistribuer le code',
            'Utiliser le code commercialement'
        ],

        required:[
            'La licence impose très peu de conditions'
        ],

        forbidden:[
            'Très peu de restrictions sont imposées par cette licence'
        ]
    }
};

function escapeHtmlEntity(text){

    const div=document.createElement('div');

    div.textContent=text;

    return div.innerHTML;
}

function buildLicenseInfo(){

    if(!licenseInfoContent){
        return;
    }

    const data=licenseInformation[licenseType];

    if(!data){

        licenseInfoContent.innerHTML=
            '<h2>'+
            escapeHtmlEntity(licenseType)+
            '</h2>'+
            '<p>Aucune fiche simplifiée n’est disponible pour cette licence.</p>'+
            '<div class="license-note">'+
            'Consulte le texte original de la licence pour connaître précisément les droits et obligations applicables.'+
            '</div>';

        return;
    }

    const checkIcon=
        '<svg class="license-status-icon license-status-ok" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">'+
        '<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">'+
        '<circle cx="12" cy="12" r="10"/>'+
        '<path d="m16 9l-5.5 5.5L8 12"/>'+
        '</g>'+
        '</svg>';

    const xIcon=
        '<svg class="license-status-icon license-status-no" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">'+
        '<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">'+
        '<circle cx="12" cy="12" r="10"/>'+
        '<path d="m15 9l-6 6m0-6l6 6"/>'+
        '</g>'+
        '</svg>';

    const alertIcon=
        '<svg class="license-status-icon license-status-warning" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">'+
        '<g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">'+
        '<circle cx="12" cy="12" r="10"/>'+
        '<path d="M12 8v4m0 4h.01"/>'+
        '</g>'+
        '</svg>';

    let html=
        '<h2>'+
        escapeHtmlEntity(data.title)+
        '</h2>';

    html+='<h3>Autorisé</h3><ul class="license-list">';

    data.allowed.forEach(v=>{
        html+=
            '<li>'+
            checkIcon+
            '<span>'+
            escapeHtmlEntity(v)+
            '</span>'+
            '</li>';
    });

    html+='</ul>';

    html+='<h3>À respecter</h3><ul class="license-list">';

    data.required.forEach(v=>{
        html+=
            '<li>'+
            alertIcon+
            '<span>'+
            escapeHtmlEntity(v)+
            '</span>'+
            '</li>';
    });

    html+='</ul>';

    html+='<h3>Restrictions</h3><ul class="license-list">';

    data.forbidden.forEach(v=>{
        html+=
            '<li>'+
            xIcon+
            '<span>'+
            escapeHtmlEntity(v)+
            '</span>'+
            '</li>';
    });

    html+='</ul>';

    html+=
        '<div class="license-note">'+
        'Résumé simplifié à titre informatif. Le texte original de la licence reste la référence juridique.'+
        '</div>';

    licenseInfoContent.innerHTML=html;
}

function toggleLicenseInfo(){

    licenseInfoMode=!licenseInfoMode;

    if(licenseInfoMode){

        buildLicenseInfo();

        if(licenseContent){
            licenseContent.classList.add('hidden');
        }

        if(licenseInfoContent){
            licenseInfoContent.classList.add('open');
        }

        if(licenseInfo){
            licenseInfo.title=
                'Afficher le texte original';
        }

    }else{

        if(licenseContent){
            licenseContent.classList.remove('hidden');
        }

        if(licenseInfoContent){
            licenseInfoContent.classList.remove('open');
        }

        if(licenseInfo){
            licenseInfo.title=
                'Informations sur la licence';
        }
    }
}

function openLicense(){

    if(!licenseOverlay){
        return;
    }

    licenseOverlay.classList.add('open');

    if(licenseClose){
        licenseClose.focus();
    }
}

function closeLicense(){

    if(licenseOverlay){
        licenseOverlay.classList.remove('open');
    }

    if(licenseInfoMode){
        toggleLicenseInfo();
    }
}

if(licenseButton){
    licenseButton.addEventListener(
        'click',
        openLicense
    );
}

if(licenseInfo){
    licenseInfo.addEventListener(
        'click',
        toggleLicenseInfo
    );
}

if(licenseClose){
    licenseClose.addEventListener(
        'click',
        closeLicense
    );
}

if(licenseDownload){
    licenseDownload.addEventListener(
        'click',
        downloadLicense
    );
}

if(licenseOverlay){

    licenseOverlay.addEventListener(
        'click',
        function(e){

            if(e.target===licenseOverlay){
                closeLicense();
            }
        }
    );
}

document.addEventListener(
    'keydown',
    function(e){

        if(
            e.key==='Escape'&&
            licenseOverlay&&
            licenseOverlay.classList.contains('open')
        ){
            closeLicense();
        }
    }
);

<?php endif; ?>

</script>

<?php include 'menu.php'; ?>
<?php include 'devtools.php'; ?>

</body>
</html>
