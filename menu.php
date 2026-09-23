<?php
declare(strict_types=1);
?>

<style>
#custom-context-menu{
    position:fixed;
    z-index:99999;
    display:none;
    min-width:245px;
    padding:5px 0;
    background:#fff;
    border:1px solid #c8c8c8;
    border-radius:7px;
    box-shadow:0 4px 16px rgba(0,0,0,.22);
    font:13px Arial,Helvetica,sans-serif;
    color:#202124;
    user-select:none;
}

#custom-context-menu.open{
    display:block;
}

.custom-menu-item{
    height:34px;
    display:flex;
    align-items:center;
    padding:0 12px;
    gap:11px;
    cursor:pointer;
    white-space:nowrap;
}

.custom-menu-item:hover{
    background:#f1f3f4;
}

.custom-menu-item:active{
    background:#e8eaed;
}

.custom-menu-item svg{
    flex:none;
    width:18px;
    height:18px;
    color:#5f6368;
}

.custom-menu-separator{
    height:1px;
    margin:5px 0;
    background:#e0e0e0;
}

.custom-menu-item.disabled{
    color:#9aa0a6;
    cursor:default;
}

.custom-menu-item.disabled svg{
    color:#9aa0a6;
}

@media(prefers-color-scheme:dark){
    #custom-context-menu{
        background:#292a2d;
        border-color:#444746;
        color:#e8eaed;
        box-shadow:0 4px 18px rgba(0,0,0,.55);
    }

    .custom-menu-item:hover{
        background:#3c4043;
    }

    .custom-menu-item:active{
        background:#45484b;
    }

    .custom-menu-item svg{
        color:#bdc1c6;
    }

    .custom-menu-separator{
        background:#444746;
    }

    .custom-menu-item.disabled,
    .custom-menu-item.disabled svg{
        color:#777;
    }
}

    .custom-menu-shortcut{
        margin-left:auto;
        padding-left:20px;
        color:#777;
        font:12px Arial,Helvetica,sans-serif;
    }

    @media(prefers-color-scheme:dark){
        .custom-menu-shortcut{
            color:#9aa0a6;
        }
    }
</style>

<div id="custom-context-menu">

 
<div class="custom-menu-item" data-action="reload">
    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 24 24"><path fill="currentColor" d="M12 20q-3.35 0-5.675-2.325T4 12t2.325-5.675T12 4q1.725 0 3.3.712T18 6.75V5q0-.425.288-.712T19 4t.713.288T20 5v5q0 .425-.288.713T19 11h-5q-.425 0-.712-.288T13 10t.288-.712T14 9h3.2q-.8-1.4-2.187-2.2T12 6Q9.5 6 7.75 7.75T6 12t1.75 4.25T12 18q1.7 0 3.113-.862t2.187-2.313q.2-.35.563-.487t.737-.013q.4.125.575.525t-.025.75q-1.025 2-2.925 3.2T12 20"></path></svg>
    <span>Actualiser</span>
    <span class="custom-menu-shortcut">Ctrl+R</span>
</div>
<div class="custom-menu-item" data-action="select-all">
    <svg xmlns="http://www.w3.org/2000/svg" width="100%" height="100%" viewBox="0 0 16 16"><path fill="currentColor" d="M3.5 11a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m9 0a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m-3 1a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1zm-6-6a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m9 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0v-3a.5.5 0 0 1 .5-.5m-9-4a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m9 0a1.5 1.5 0 1 1 0 3a1.5 1.5 0 0 1 0-3m-3 1a.5.5 0 0 1 0 1h-3a.5.5 0 0 1 0-1z"></path></svg>
    <span>Sélectionner tout le texte</span>
    <span class="custom-menu-shortcut">Ctrl+A</span>
</div>

<div class="custom-menu-separator"></div>

<div class="custom-menu-item" data-action="download-source">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path fill="currentColor" d="M11.625 15.513q-.175-.063-.325-.213l-3.6-3.6q-.3-.3-.288-.7t.288-.7q.3-.3.713-.312t.712.287L11 12.15V5q0-.425.288-.712T12 4t.713.288T13 5v7.15l1.875-1.875q.3-.3.713-.288t.712.313q.275.3.288.7t-.288.7l-3.6 3.6q-.15.15-.325.213t-.375.062M6 20q-.825 0-1.412-.587T4 18v-2q0-.425.288-.712T5 15t.713.288T6 16v2h12v-2q0-.425.288-.712T19 15t.713.288T20 16v2q0 .825-.587 1.413T18 20z"/>
    </svg>
    <span>Télécharger le code source</span>
    <span class="custom-menu-shortcut">Ctrl+S</span>
</div>

<div class="custom-menu-item" data-action="download-archive">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path fill="currentColor" d="M4 20q-.825 0-1.412-.587T2 18V6q0-.825.588-1.412T4 4h6l2 2h8q.825 0 1.413.588T22 8v10q0 .825-.587 1.413T20 20zm10-2h2v-2h2v-2h-2v-2h2v-2h-2V8h-2v2h2v2h-2v2h2v2h-2z"/>
    </svg>
    <span>Télécharger l'archive</span>
</div>
    
<div class="custom-menu-separator"></div>
    
<?php if(isset($githubLink) && $githubLink!==''): ?>
<div class="custom-menu-item" data-action="github">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path fill="currentColor" d="M12 2A10 10 0 0 0 2 12c0 4.42 2.87 8.17 6.84 9.5c.5.08.66-.23.66-.5v-1.69c-2.77.6-3.36-1.34-3.36-1.34c-.46-1.16-1.11-1.47-1.11-1.47c-.91-.62.07-.6.07-.6c1 .07 1.53 1.03 1.53 1.03c.87 1.52 2.34 1.07 2.91.83c.09-.65.35-1.09.63-1.34c-2.22-.25-4.55-1.11-4.55-4.92c0-1.11.38-2 1.03-2.71c-.1-.25-.45-1.29.1-2.64c0 0 .84-.27 2.75 1.02c.79-.22 1.65-.33 2.5-.33s1.71.11 2.5.33c1.91-1.29 2.75-1.02 2.75-1.02c.55 1.35.2 2.39.1 2.64c.65.71 1.03 1.6 1.03 2.71c0 3.82-2.34 4.66-4.57 4.91c.36.31.69.92.69 1.85V21c0 .27.16.59.67.5C19.14 20.16 22 16.42 22 12A10 10 0 0 0 12 2"/>
    </svg>
    <span>Ouvrir le dépôt GitHub</span>
</div>
<?php endif; ?>
 

</div>

<script>
(function(){

    const menu=document.getElementById('custom-context-menu');

    if(!menu)return;

    function hideMenu(){
        menu.classList.remove('open');
    }

    function showMenu(x,y){

        menu.classList.add('open');

        const rect=menu.getBoundingClientRect();

        if(x+rect.width>window.innerWidth){
            x=window.innerWidth-rect.width-6;
        }

        if(y+rect.height>window.innerHeight){
            y=window.innerHeight-rect.height-6;
        }

        if(x<6)x=6;
        if(y<6)y=6;

        menu.style.left=x+'px';
        menu.style.top=y+'px';
    }

    document.addEventListener('contextmenu',function(e){
        e.preventDefault();
        showMenu(e.clientX,e.clientY);
    });

    document.addEventListener('mousedown',function(e){
        if(!menu.contains(e.target)){
            hideMenu();
        }
    });

    document.addEventListener('keydown',function(e){
        if(e.key==='Escape'){
            hideMenu();
        }
    });

    window.addEventListener('resize',hideMenu);
    window.addEventListener('scroll',hideMenu,true);

    menu.addEventListener('click',function(e){

        const item=e.target.closest('.custom-menu-item');

        if(!item)return;

        const action=item.dataset.action;

        hideMenu();

        if(action==='reload'){
            window.location.reload();
            return;
        }

        if(action==='select-all'){

            const code=document.getElementById('code');

            if(code){
                const selection=window.getSelection();
                const range=document.createRange();

                range.selectNodeContents(code);

                selection.removeAllRanges();
                selection.addRange(range);
            }

            return;
        }

        if(action==='download-source'){

            document.dispatchEvent(
                new KeyboardEvent('keydown',{
                    key:'s',
                    code:'KeyS',
                    ctrlKey:true,
                    bubbles:true
                })
            );

            return;
        }

        if(action==='download-archive'){

            const button=document.getElementById('sourceZipButton');

            if(button){
                button.click();
            }

            return;
        }

        if(action==='github'){

            const button=document.querySelector('.github-button');

            if(button && button.href){
                window.open(button.href,'_blank','noopener,noreferrer');
            }

            return;
        }

    });

})();
</script>
