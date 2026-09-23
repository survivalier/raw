<?php
declare(strict_types=1);
?>

<div id="labs-devtools">

    <div id="labs-devtools-resizer"></div>

    <!-- TOOLBAR -->
    <div class="ldt-toolbar">

        <div class="ldt-toolbar-left">

            <button class="ldt-icon-button" id="ldt-back" title="Back">
                <svg viewBox="0 0 24 24">
                    <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                </svg>
            </button>

            <button class="ldt-icon-button" id="ldt-forward" title="Forward">
                <svg viewBox="0 0 24 24">
                    <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>
                </svg>
            </button>

            <button class="ldt-icon-button" id="ldt-reload" title="Reload">
                <svg viewBox="0 0 24 24">
                    <path d="M17.65 6.35A7.95 7.95 0 0 0 12 4V1L8 5l4 4V6a6 6 0 1 1-6 6H4a8 8 0 1 0 13.65-5.65z"/>
                </svg>
            </button>

        </div>

        <div class="ldt-toolbar-center">

            <button class="ldt-inspect-button" id="ldt-inspect" title="Select an element">
                <svg viewBox="0 0 24 24">
                    <path d="M4 2l15 9-6 1-3 7-6-17zm3.2 3.9L9.7 16l2-4.6 4.1-.7L7.2 5.9z"/>
                </svg>
            </button>

        </div>

        <div class="ldt-toolbar-right">

            <button class="ldt-icon-button" id="ldt-more" title="More options">
                <svg viewBox="0 0 24 24">
                    <circle cx="5" cy="12" r="1.7"/>
                    <circle cx="12" cy="12" r="1.7"/>
                    <circle cx="19" cy="12" r="1.7"/>
                </svg>
            </button>

            <button class="ldt-icon-button" id="ldt-dock" title="Dock side">
                <svg viewBox="0 0 24 24">
                    <path d="M3 4h18v16H3V4zm16 2h-5v12h5V6zM5 6v12h7V6H5z"/>
                </svg>
            </button>

            <button class="ldt-icon-button" id="ldt-close" title="Close DevTools">
                <svg viewBox="0 0 24 24">
                    <path d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.3 19.71 2.89 18.3 9.17 12 2.89 5.7 4.3 4.29l6.29 6.3 6.29-6.3z"/>
                </svg>
            </button>

        </div>

    </div>


    <!-- MAIN TABS -->
    <div class="ldt-tabs">

        <button class="ldt-tab active" data-panel="elements">
            Elements
        </button>

        <button class="ldt-tab" data-panel="console">
            Console
        </button>

        <button class="ldt-tab" data-panel="sources">
            Sources
        </button>

        <button class="ldt-tab" data-panel="network">
            Network
        </button>

        <button class="ldt-tab" data-panel="application">
            Application
        </button>

        <button class="ldt-tab" data-panel="security">
            Security
        </button>

        <button class="ldt-tab" data-panel="lighthouse">
            Lighthouse
        </button>

    </div>


    <!-- ELEMENTS -->
    <section class="ldt-panel active" data-panel-content="elements">

        <div class="ldt-elements">

            <div class="ldt-dom-toolbar">

                <button class="ldt-small-icon" title="Add">
                    +
                </button>

                <button class="ldt-small-icon" title="Search">
                    <svg viewBox="0 0 24 24">
                        <path d="M9.5 3a6.5 6.5 0 0 1 5.18 10.42L20.1 18.84l-1.26 1.26-5.42-5.42A6.5 6.5 0 1 1 9.5 3zm0 2a4.5 4.5 0 1 0 0 9 4.5 4.5 0 0 0 0-9z"/>
                    </svg>
                </button>

            </div>

            <div class="ldt-dom-tree" id="ldt-dom-tree"></div>

        </div>


        <div class="ldt-styles">

            <div class="ldt-style-tabs">

                <button class="ldt-style-tab active">
                    Styles
                </button>

                <button class="ldt-style-tab">
                    Computed
                </button>

                <button class="ldt-style-tab">
                    Layout
                </button>

            </div>

            <div class="ldt-style-search">

                <svg viewBox="0 0 24 24">
                    <path d="M9.5 3a6.5 6.5 0 0 1 5.18 10.42L20.1 18.84l-1.26 1.26-5.42-5.42A6.5 6.5 0 1 1 9.5 3z"/>
                </svg>

                <input
                    id="ldt-style-filter"
                    placeholder="Filter"
                    autocomplete="off"
                >

            </div>

            <div id="ldt-style-content">

                <div class="ldt-style-section">

                    <div class="ldt-style-selector">
                        <span class="ldt-arrow">▼</span>
                        element.style
                    </div>

                    <div class="ldt-property">

                        <span class="ldt-property-name">
                            /* Styles will appear here */
                        </span>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- CONSOLE -->
    <section class="ldt-panel" data-panel-content="console">

        <div class="ldt-console-toolbar">

            <button id="ldt-clear-console">
                Clear console
            </button>

            <label>
                <input type="checkbox" id="ldt-console-persist">
                Preserve log
            </label>

        </div>

        <div
            class="ldt-console-output"
            id="ldt-console-output"
        ></div>

        <div class="ldt-console-input">

            <span class="ldt-prompt">&gt;</span>

            <textarea
                id="ldt-console-command"
                spellcheck="false"
                autocomplete="off"
                placeholder="Enter JavaScript expression"
            ></textarea>

        </div>

    </section>


    <!-- SOURCES -->
    <section class="ldt-panel" data-panel-content="sources">

        <div class="ldt-sources">

            <aside class="ldt-sources-sidebar">

                <div class="ldt-sidebar-title">
                    Page
                </div>

                <div
                    class="ldt-source-file active"
                    id="ldt-current-source"
                >
                    <svg viewBox="0 0 24 24">
                        <path d="M6 2h9l5 5v15H6V2zm8 2v5h5M8 12h8M8 16h8"/>
                    </svg>

                    <span>Current page</span>
                </div>

            </aside>

            <div class="ldt-source-editor">

                <div class="ldt-editor-tabs">

                    <div class="ldt-editor-tab active">
                        <span>Current page</span>

                        <button>×</button>
                    </div>

                </div>

                <pre id="ldt-source-code"></pre>

            </div>

        </div>

    </section>


    <!-- NETWORK -->
    <section class="ldt-panel" data-panel-content="network">

        <div class="ldt-network-toolbar">

            <button class="ldt-network-clear" id="ldt-clear-network">
                Clear
            </button>

            <input
                id="ldt-network-filter"
                placeholder="Filter"
                autocomplete="off"
            >

        </div>

        <div class="ldt-network-head">

            <span>Name</span>
            <span>Status</span>
            <span>Type</span>
            <span>Size</span>
            <span>Time</span>

        </div>

        <div id="ldt-network-list"></div>

    </section>


    <!-- APPLICATION -->
    <section class="ldt-panel" data-panel-content="application">

        <div class="ldt-application">

            <aside class="ldt-application-sidebar">

                <div class="ldt-sidebar-title">
                    Application
                </div>

                <button data-storage="local">
                    <span>▸</span>
                    Local Storage
                </button>

                <button data-storage="session">
                    <span>▸</span>
                    Session Storage
                </button>

                <button data-storage="cookies">
                    <span>▸</span>
                    Cookies
                </button>

            </aside>

            <div
                class="ldt-storage-content"
                id="ldt-storage-content"
            >
                Select a storage area
            </div>

        </div>

    </section>


    <!-- SECURITY -->
    <section class="ldt-panel" data-panel-content="security">

        <div class="ldt-empty-panel">

            <svg viewBox="0 0 24 24">
                <path d="M12 2 4 5v6c0 5 3.4 9.7 8 11 4.6-1.3 8-6 8-11V5l-8-3zm0 2.2 6 2.2V11c0 3.8-2.4 7.4-6 8.7C8.4 18.4 6 14.8 6 11V6.4l6-2.2z"/>
            </svg>

            <strong>This page is secure</strong>

            <span>
                Connection security information
            </span>

        </div>

    </section>


    <!-- LIGHTHOUSE -->
    <section class="ldt-panel" data-panel-content="lighthouse">

        <div class="ldt-lighthouse">

            <div class="ldt-lighthouse-header">
                <strong>Lighthouse</strong>

                <button id="ldt-run-lighthouse">
                    Analyze page
                </button>
            </div>

            <div id="ldt-lighthouse-result">

                <div class="ldt-lighthouse-card">
                    Performance
                    <strong>—</strong>
                </div>

                <div class="ldt-lighthouse-card">
                    Accessibility
                    <strong>—</strong>
                </div>

                <div class="ldt-lighthouse-card">
                    Best Practices
                    <strong>—</strong>
                </div>

                <div class="ldt-lighthouse-card">
                    SEO
                    <strong>—</strong>
                </div>

            </div>

        </div>

    </section>

</div>


<style>

#labs-devtools{

    --ldt-blue:#1a73e8;
    --ldt-blue-light:#e8f0fe;
    --ldt-text:#202124;
    --ldt-secondary:#5f6368;
    --ldt-border:#dadce0;
    --ldt-background:#fff;
    --ldt-toolbar:#f8f9fa;
    --ldt-hover:#f1f3f4;
    --ldt-selected:#d2e3fc;

    position:fixed;

    top:0;
    right:0;

    width:52vw;
    min-width:620px;

    height:100vh;

    z-index:2147483647;

    display:none;
    flex-direction:column;

    background:var(--ldt-background);

    color:var(--ldt-text);

    border-left:1px solid #c7c7c7;

    box-shadow:
        -3px 0 12px rgba(0,0,0,.16);

    font-family:
        Arial,
        "Roboto",
        sans-serif;

    font-size:12px;

    line-height:1.4;

    overflow:hidden;

    box-sizing:border-box;

}

#labs-devtools *,
#labs-devtools *::before,
#labs-devtools *::after{

    box-sizing:border-box;

}

#labs-devtools.open{

    display:flex;

}


/* RESIZER */

#labs-devtools-resizer{

    position:absolute;

    left:-4px;
    top:0;

    width:8px;
    height:100%;

    cursor:ew-resize;

    z-index:100;

}


/* TOOLBAR */

.ldt-toolbar{

    height:36px;

    flex:none;

    display:flex;

    align-items:center;

    justify-content:space-between;

    background:#f8f9fa;

    border-bottom:1px solid var(--ldt-border);

}

.ldt-toolbar-left,
.ldt-toolbar-right,
.ldt-toolbar-center{

    display:flex;

    align-items:center;

    height:100%;

}

.ldt-toolbar-center{

    position:absolute;

    left:50%;

    transform:translateX(-50%);

}

.ldt-icon-button{

    width:34px;
    height:34px;

    display:flex;

    align-items:center;
    justify-content:center;

    border:0;

    background:transparent;

    color:#5f6368;

    cursor:pointer;

}

.ldt-icon-button:hover{

    background:#e8eaed;

}

.ldt-icon-button:active{

    background:#dadce0;

}

.ldt-icon-button svg{

    width:18px;
    height:18px;

    fill:currentColor;

}

.ldt-inspect-button{

    width:32px;
    height:30px;

    border:0;

    background:transparent;

    color:#5f6368;

    cursor:pointer;

}

.ldt-inspect-button:hover{

    background:#e8eaed;

}

.ldt-inspect-button svg{

    width:18px;
    height:18px;

    fill:currentColor;

}


/* TABS */

.ldt-tabs{

    height:32px;

    flex:none;

    display:flex;

    align-items:stretch;

    background:#fff;

    border-bottom:1px solid var(--ldt-border);

    padding-left:4px;

    overflow-x:auto;

    scrollbar-width:none;

}

.ldt-tabs::-webkit-scrollbar{

    display:none;

}

.ldt-tab{

    position:relative;

    height:32px;

    padding:0 12px;

    border:0;

    background:transparent;

    color:#5f6368;

    font-size:12px;

    cursor:pointer;

    white-space:nowrap;

}

.ldt-tab:hover{

    color:#202124;

    background:#f8f9fa;

}

.ldt-tab.active{

    color:#1a73e8;

}

.ldt-tab.active::after{

    content:"";

    position:absolute;

    bottom:-1px;
    left:8px;
    right:8px;

    height:2px;

    background:#1a73e8;

}


/* PANELS */

.ldt-panel{

    display:none;

    flex:1;

    min-height:0;

    overflow:hidden;

}

.ldt-panel.active{

    display:flex;

}


/* ELEMENTS */

.ldt-elements{

    width:58%;

    min-width:320px;

    display:flex;

    flex-direction:column;

    border-right:1px solid var(--ldt-border);

}

.ldt-dom-toolbar{

    height:30px;

    flex:none;

    display:flex;

    align-items:center;

    padding:0 4px;

    background:#fff;

    border-bottom:1px solid #eeeeee;

}

.ldt-small-icon{

    width:28px;
    height:26px;

    border:0;

    background:transparent;

    color:#5f6368;

    cursor:pointer;

    display:flex;

    align-items:center;
    justify-content:center;

    font-size:18px;

}

.ldt-small-icon:hover{

    background:#f1f3f4;

}

.ldt-small-icon svg{

    width:15px;
    height:15px;

    fill:currentColor;

}

.ldt-dom-tree{

    flex:1;

    overflow:auto;

    padding:5px 0;

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    font-size:11px;

}

.ldt-dom-node{

    position:relative;

    min-height:20px;

    padding:2px 6px;

    white-space:nowrap;

    cursor:pointer;

}

.ldt-dom-node:hover{

    background:#f1f3f4;

}

.ldt-dom-node.selected{

    background:#cfe3ff;

}

.ldt-dom-node .tag{

    color:#881280;

}

.ldt-dom-node .attribute{

    color:#994500;

}

.ldt-dom-node .value{

    color:#1a1aa6;

}

.ldt-dom-node .text{

    color:#333;

}

.ldt-dom-arrow{

    display:inline-block;

    width:14px;

    color:#777;

}

.ldt-dom-comment{

    color:#5f6368;

}


/* STYLES */

.ldt-styles{

    flex:1;

    min-width:260px;

    display:flex;

    flex-direction:column;

    background:#fff;

}

.ldt-style-tabs{

    height:30px;

    display:flex;

    border-bottom:1px solid var(--ldt-border);

    flex:none;

}

.ldt-style-tab{

    border:0;

    background:#fff;

    padding:0 10px;

    color:#5f6368;

    cursor:pointer;

    position:relative;

}

.ldt-style-tab:hover{

    background:#f8f9fa;

}

.ldt-style-tab.active{

    color:#1a73e8;

}

.ldt-style-tab.active::after{

    content:"";

    position:absolute;

    bottom:0;
    left:8px;
    right:8px;

    height:2px;

    background:#1a73e8;

}

.ldt-style-search{

    height:30px;

    margin:7px 8px;

    display:flex;

    align-items:center;

    border:1px solid #dadce0;

    border-radius:2px;

    padding:0 7px;

}

.ldt-style-search:focus-within{

    border-color:#1a73e8;

    box-shadow:
        0 0 0 1px #1a73e8;

}

.ldt-style-search svg{

    width:14px;
    height:14px;

    fill:#5f6368;

    margin-right:6px;

}

.ldt-style-search input{

    border:0;
    outline:0;

    flex:1;

    font-size:12px;

    color:#202124;

}

#ldt-style-content{

    flex:1;

    overflow:auto;

}

.ldt-style-section{

    border-top:1px solid #eeeeee;

    padding:8px;

}

.ldt-style-selector{

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    color:#5f6368;

    margin-bottom:5px;

}

.ldt-arrow{

    color:#5f6368;

    margin-right:4px;

}

.ldt-property{

    padding-left:17px;

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    line-height:20px;

}

.ldt-property-name{

    color:#5f6368;

}


/* CONSOLE */

.ldt-console-toolbar{

    height:34px;

    flex:none;

    display:flex;

    align-items:center;

    gap:14px;

    padding:0 8px;

    background:#fff;

    border-bottom:1px solid var(--ldt-border);

}

.ldt-console-toolbar button{

    border:1px solid #dadce0;

    background:#fff;

    border-radius:2px;

    padding:4px 9px;

    color:#3c4043;

    cursor:pointer;

}

.ldt-console-toolbar button:hover{

    background:#f1f3f4;

}

.ldt-console-toolbar label{

    color:#5f6368;

    display:flex;

    align-items:center;

    gap:5px;

}

.ldt-console-output{

    flex:1;

    overflow:auto;

    background:#202124;

    color:#e8eaed;

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    font-size:12px;

}

.ldt-console-line{

    min-height:21px;

    padding:3px 9px;

    border-bottom:1px solid #303134;

    white-space:pre-wrap;

    word-break:break-word;

}

.ldt-console-line.error{

    color:#f28b82;

    background:#3c2020;

}

.ldt-console-line.warn{

    color:#fdd663;

}

.ldt-console-line.result{

    color:#8ab4f8;

}

.ldt-console-line.input{

    color:#e8eaed;

}

.ldt-console-input{

    min-height:38px;

    display:flex;

    align-items:flex-start;

    background:#202124;

    border-top:1px solid #3c4043;

    padding:7px 9px;

    flex:none;

}

.ldt-prompt{

    color:#8ab4f8;

    font-family:monospace;

    margin-right:8px;

}

#ldt-console-command{

    flex:1;

    min-height:20px;
    max-height:120px;

    resize:none;

    overflow:auto;

    border:0;
    outline:0;

    background:transparent;

    color:#e8eaed;

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    font-size:12px;

}


/* SOURCES */

.ldt-sources{

    width:100%;
    height:100%;

    display:flex;

}

.ldt-sources-sidebar{

    width:190px;

    flex:none;

    border-right:1px solid var(--ldt-border);

    background:#f8f9fa;

    overflow:auto;

}

.ldt-sidebar-title{

    padding:8px 12px;

    font-size:11px;

    color:#5f6368;

    text-transform:uppercase;

}

.ldt-source-file{

    height:30px;

    display:flex;

    align-items:center;

    gap:7px;

    padding:0 10px;

    cursor:pointer;

}

.ldt-source-file:hover{

    background:#e8eaed;

}

.ldt-source-file.active{

    background:#d2e3fc;

}

.ldt-source-file svg{

    width:15px;
    height:15px;

    fill:none;

    stroke:#5f6368;

    stroke-width:1.7;

}

.ldt-source-editor{

    flex:1;

    min-width:0;

    display:flex;

    flex-direction:column;

}

.ldt-editor-tabs{

    height:31px;

    flex:none;

    background:#f1f3f4;

    border-bottom:1px solid var(--ldt-border);

}

.ldt-editor-tab{

    display:inline-flex;

    align-items:center;

    height:31px;

    padding:0 8px 0 12px;

    background:#fff;

    border-right:1px solid var(--ldt-border);

    border-top:2px solid #1a73e8;

}

.ldt-editor-tab button{

    margin-left:10px;

    border:0;

    background:transparent;

    color:#5f6368;

    cursor:pointer;

}

#ldt-source-code{

    flex:1;

    overflow:auto;

    margin:0;

    padding:10px 14px;

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    font-size:11px;

    line-height:18px;

    color:#202124;

    background:#fff;

    tab-size:4;

}


/* NETWORK */

.ldt-network-toolbar{

    height:38px;

    flex:none;

    display:flex;

    align-items:center;

    gap:8px;

    padding:0 8px;

    border-bottom:1px solid var(--ldt-border);

}

.ldt-network-toolbar button{

    border:1px solid #dadce0;

    background:#fff;

    padding:4px 9px;

    border-radius:2px;

    cursor:pointer;

}

.ldt-network-toolbar button:hover{

    background:#f1f3f4;

}

.ldt-network-toolbar input{

    width:180px;

    height:25px;

    border:1px solid #dadce0;

    border-radius:2px;

    padding:0 7px;

    outline:none;

}

.ldt-network-toolbar input:focus{

    border-color:#1a73e8;

}

.ldt-network-head{

    display:grid;

    grid-template-columns:
        minmax(150px,1fr)
        70px
        90px
        70px
        70px;

    height:28px;

    align-items:center;

    padding:0 8px;

    background:#f8f9fa;

    border-bottom:1px solid var(--ldt-border);

    color:#5f6368;

}

.ldt-network-row{

    display:grid;

    grid-template-columns:
        minmax(150px,1fr)
        70px
        90px
        70px
        70px;

    min-height:27px;

    align-items:center;

    padding:0 8px;

    border-bottom:1px solid #eeeeee;

    font-family:
        "Roboto Mono",
        Consolas,
        monospace;

    font-size:11px;

}

.ldt-network-row:hover{

    background:#f1f3f4;

}

.ldt-network-status.ok{

    color:#188038;

}

.ldt-network-status.error{

    color:#d93025;

}


/* APPLICATION */

.ldt-application{

    display:flex;

    width:100%;
    height:100%;

}

.ldt-application-sidebar{

    width:190px;

    flex:none;

    background:#f8f9fa;

    border-right:1px solid var(--ldt-border);

    padding-top:4px;

}

.ldt-application-sidebar button{

    width:100%;

    height:30px;

    padding:0 12px;

    border:0;

    background:transparent;

    color:#3c4043;

    text-align:left;

    cursor:pointer;

}

.ldt-application-sidebar button:hover{

    background:#e8eaed;

}

.ldt-application-sidebar button span{

    color:#5f6368;

    margin-right:5px;

}

.ldt-storage-content{

    flex:1;

    overflow:auto;

    padding:12px;

    color:#5f6368;

}

.ldt-storage-table{

    width:100%;

    border-collapse:collapse;

    color:#202124;

}

.ldt-storage-table th{

    background:#f8f9fa;

    text-align:left;

    font-weight:500;

}

.ldt-storage-table th,
.ldt-storage-table td{

    padding:7px;

    border:1px solid #dadce0;

}


/* SECURITY */

.ldt-empty-panel{

    width:100%;

    height:100%;

    display:flex;

    flex-direction:column;

    align-items:center;
    justify-content:center;

    gap:8px;

    color:#5f6368;

}

.ldt-empty-panel svg{

    width:52px;
    height:52px;

    fill:#1a73e8;

    margin-bottom:8px;

}

.ldt-empty-panel strong{

    color:#202124;

    font-size:14px;

}


/* LIGHTHOUSE */

.ldt-lighthouse{

    width:100%;
    padding:20px;

}

.ldt-lighthouse-header{

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding-bottom:15px;

    border-bottom:1px solid var(--ldt-border);

}

.ldt-lighthouse-header strong{

    font-size:16px;

}

.ldt-lighthouse-header button{

    border:1px solid #dadce0;

    background:#1a73e8;

    color:#fff;

    padding:7px 14px;

    border-radius:2px;

    cursor:pointer;

}

.ldt-lighthouse-header button:hover{

    background:#1765cc;

}

#ldt-lighthouse-result{

    display:grid;

    grid-template-columns:
        repeat(2,1fr);

    gap:12px;

    margin-top:20px;

}

.ldt-lighthouse-card{

    min-height:110px;

    border:1px solid var(--ldt-border);

    border-radius:4px;

    padding:14px;

    display:flex;

    flex-direction:column;

    justify-content:space-between;

}

.ldt-lighthouse-card strong{

    font-size:30px;

    font-weight:400;

}


/* SCROLLBARS */

#labs-devtools ::-webkit-scrollbar{

    width:10px;
    height:10px;

}

#labs-devtools ::-webkit-scrollbar-track{

    background:#fff;

}

#labs-devtools ::-webkit-scrollbar-thumb{

    background:#c1c1c1;

    border:2px solid #fff;

}

#labs-devtools ::-webkit-scrollbar-thumb:hover{

    background:#a8a8a8;

}

</style>


<script>

(function(){

    'use strict';

    const devtools=
        document.getElementById(
            'labs-devtools'
        );

    if(!devtools) return;


    const closeButton=
        document.getElementById(
            'ldt-close'
        );

    const inspectButton=
        document.getElementById(
            'ldt-inspect'
        );

    const dockButton=
        document.getElementById(
            'ldt-dock'
        );


    /* =========================
       OPEN / CLOSE
    ========================= */

    function openDevTools(){

        devtools.classList.add('open');

        buildDOMTree();

        loadSource();

    }


    function closeDevTools(){

        devtools.classList.remove('open');

    }


    function toggleDevTools(){

        if(
            devtools.classList.contains('open')
        ){

            closeDevTools();

        }else{

            openDevTools();

        }

    }


    closeButton.addEventListener(
        'click',
        closeDevTools
    );


    /* =========================
       KEYBOARD SHORTCUTS
    ========================= */

    document.addEventListener(
        'keydown',
        function(event){

            if(
                event.key==='F12' ||
                (
                    event.ctrlKey &&
                    event.shiftKey &&
                    event.key.toLowerCase()==='i'
                )
            ){

                event.preventDefault();
                event.stopPropagation();

                toggleDevTools();

            }

        },
        true
    );


    /* =========================
       TABS
    ========================= */

    devtools
        .querySelectorAll('.ldt-tab')
        .forEach(function(tab){

            tab.addEventListener(
                'click',
                function(){

                    const panel=
                        tab.dataset.panel;

                    devtools
                        .querySelectorAll('.ldt-tab')
                        .forEach(function(item){

                            item.classList.remove(
                                'active'
                            );

                        });

                    devtools
                        .querySelectorAll(
                            '.ldt-panel'
                        )
                        .forEach(function(item){

                            item.classList.remove(
                                'active'
                            );

                        });

                    tab.classList.add('active');

                    const target=
                        devtools.querySelector(
                            '[data-panel-content="'+
                            panel+
                            '"]'
                        );

                    if(target){

                        target.classList.add(
                            'active'
                        );

                    }

                }

            );

        });


    /* =========================
       DOM TREE
    ========================= */

    function buildDOMTree(){

        const container=
            document.getElementById(
                'ldt-dom-tree'
            );

        if(!container) return;

        container.innerHTML='';


        function addNode(
            element,
            parent,
            depth
        ){

            if(
                !element ||
                element.nodeType!==1
            ){

                return;

            }


            const row=
                document.createElement(
                    'div'
                );

            row.className=
                'ldt-dom-node';

            row.style.paddingLeft=
                (depth*16+5)+'px';


            const hasChildren=
                element.children.length>0;


            const arrow=
                document.createElement(
                    'span'
                );

            arrow.className=
                'ldt-dom-arrow';

            arrow.textContent=
                hasChildren
                    ? '▼'
                    : '';


            row.appendChild(arrow);


            const tag=
                document.createElement(
                    'span'
                );

            tag.className='tag';

            tag.textContent=
                '<'+
                element.tagName.toLowerCase();

            row.appendChild(tag);


            Array.from(
                element.attributes
            ).forEach(function(attribute){

                const attr=
                    document.createElement(
                        'span'
                    );

                attr.className=
                    'attribute';

                attr.textContent=
                    ' '+
                    attribute.name+
                    '="';

                row.appendChild(attr);


                const value=
                    document.createElement(
                        'span'
                    );

                value.className=
                    'value';

                value.textContent=
                    attribute.value;

                row.appendChild(value);


                const quote=
                    document.createElement(
                        'span'
                    );

                quote.className=
                    'attribute';

                quote.textContent='"';

                row.appendChild(quote);

            });


            const end=
                document.createElement(
                    'span'
                );

            end.className='tag';

            end.textContent='>';

            row.appendChild(end);


            row.addEventListener(
                'click',
                function(event){

                    event.stopPropagation();

                    devtools
                        .querySelectorAll(
                            '.ldt-dom-node.selected'
                        )
                        .forEach(function(item){

                            item.classList.remove(
                                'selected'
                            );

                        });

                    row.classList.add(
                        'selected'
                    );

                    showElementStyles(
                        element
                    );

                }
            );


            parent.appendChild(row);


            Array.from(
                element.children
            ).forEach(function(child){

                addNode(
                    child,
                    parent,
                    depth+1
                );

            });

        }


        addNode(
            document.documentElement,
            container,
            0
        );

    }


    /* =========================
       STYLES
    ========================= */

    function showElementStyles(element){

        const content=
            document.getElementById(
                'ldt-style-content'
            );

        if(!content) return;


        content.innerHTML='';


        const section=
            document.createElement(
                'div'
            );

        section.className=
            'ldt-style-section';


        const selector=
            document.createElement(
                'div'
            );

        selector.className=
            'ldt-style-selector';

        selector.innerHTML=
            '<span class="ldt-arrow">▼</span>'+
            'element.style';

        section.appendChild(selector);


        const styles=
            getComputedStyle(
                element
            );


        for(
            let i=0;
            i<styles.length;
            i++
        ){

            const property=
                styles[i];

            const value=
                styles.getPropertyValue(
                    property
                );


            const row=
                document.createElement(
                    'div'
                );

            row.className=
                'ldt-property';


            const name=
                document.createElement(
                    'span'
                );

            name.style.color=
                '#881280';

            name.textContent=
                property;

            row.appendChild(name);


            const separator=
                document.createElement(
                    'span'
                );

            separator.textContent=': ';

            row.appendChild(separator);


            const valueNode=
                document.createElement(
                    'span'
                );

            valueNode.style.color=
                '#1a1aa6';

            valueNode.textContent=
                value;

            row.appendChild(valueNode);


            const semicolon=
                document.createElement(
                    'span'
                );

            semicolon.textContent=';';

            row.appendChild(
                semicolon
            );


            section.appendChild(row);

        }


        content.appendChild(section);

    }


    /* =========================
       INSPECT MODE
    ========================= */

    let inspectMode=false;

    inspectButton.addEventListener(
        'click',
        function(){

            inspectMode=
                !inspectMode;

            inspectButton.style.color=
                inspectMode
                    ? '#1a73e8'
                    : '#5f6368';

        }
    );


    document.addEventListener(
        'mousemove',
        function(event){

            if(!inspectMode)
                return;

            if(
                devtools.contains(
                    event.target
                )
            ){

                return;

            }


            const element=
                document.elementFromPoint(
                    event.clientX,
                    event.clientY
                );

            if(!element)
                return;


            element.style.outline=
                '2px solid #1a73e8';

            element.style.outlineOffset=
                '-2px';


            element.dataset.ldtInspect=
                '1';


        },
        true
    );


    document.addEventListener(
        'click',
        function(event){

            if(!inspectMode)
                return;

            if(
                devtools.contains(
                    event.target
                )
            ){

                return;

            }


            event.preventDefault();
            event.stopPropagation();


            const element=
                event.target;


            inspectMode=false;

            inspectButton.style.color=
                '#5f6368';


            buildDOMTree();

            showElementStyles(
                element
            );

        },
        true
    );


    /* =========================
       CONSOLE
    ========================= */

    const consoleOutput=
        document.getElementById(
            'ldt-console-output'
        );

    const consoleInput=
        document.getElementById(
            'ldt-console-command'
        );


    function consolePrint(
        type,
        value
    ){

        if(!consoleOutput)
            return;


        const line=
            document.createElement(
                'div'
            );

        line.className=
            'ldt-console-line '+
            type;


        if(
            typeof value==='object' &&
            value!==null
        ){

            try{

                line.textContent=
                    JSON.stringify(
                        value,
                        null,
                        2
                    );

            }catch(error){

                line.textContent=
                    String(value);

            }

        }else{

            line.textContent=
                String(value);

        }


        consoleOutput.appendChild(
            line
        );

        consoleOutput.scrollTop=
            consoleOutput.scrollHeight;

    }


    consoleInput.addEventListener(
        'keydown',
        function(event){

            if(
                event.key!=='Enter' ||
                event.shiftKey
            ){

                return;

            }


            event.preventDefault();


            const code=
                consoleInput.value.trim();


            if(!code)
                return;


            consolePrint(
                'input',
                '> '+code
            );


            consoleInput.value='';


            try{

                const result=
                    window.eval(
                        code
                    );


                if(
                    result!==undefined
                ){

                    consolePrint(
                        'result',
                        result
                    );

                }

            }catch(error){

                consolePrint(
                    'error',
                    error.toString()
                );

            }

        }
    );


    document
        .getElementById(
            'ldt-clear-console'
        )
        .addEventListener(
            'click',
            function(){

                consoleOutput.innerHTML='';

            }
        );


    /* =========================
       CAPTURE CONSOLE
    ========================= */

    ['log','warn','error','info']
        .forEach(function(type){

            const original=
                console[type];

            console[type]=function(){

                original.apply(
                    console,
                    arguments
                );


                if(
                    devtools.classList.contains(
                        'open'
                    )
                ){

                    Array.from(
                        arguments
                    ).forEach(function(value){

                        consolePrint(
                            type==='error'
                                ? 'error'
                                : type==='warn'
                                    ? 'warn'
                                    : 'input',
                            value
                        );

                    });

                }

            };

        });


    /* =========================
       SOURCE
    ========================= */

    function loadSource(){

        const source=
            document.getElementById(
                'ldt-source-code'
            );

        if(!source) return;


        try{

            source.textContent=
                document.documentElement
                    .outerHTML;

        }catch(error){

            source.textContent=
                error.toString();

        }

    }


    /* =========================
       NETWORK
    ========================= */

    const networkList=
        document.getElementById(
            'ldt-network-list'
        );


    function addNetworkEntry(
        url,
        status,
        type,
        size,
        time
    ){

        if(!networkList)
            return;


        const row=
            document.createElement(
                'div'
            );

        row.className=
            'ldt-network-row';


        const statusClass=
            status>=200 &&
            status<400
                ? 'ok'
                : 'error';


        row.innerHTML=
            '<span></span>'+
            '<span class="ldt-network-status '+
            statusClass+
            '"></span>'+
            '<span></span>'+
            '<span></span>'+
            '<span></span>';


        row.children[0]
            .textContent=
                url;

        row.children[1]
            .textContent=
                status;

        row.children[2]
            .textContent=
                type;

        row.children[3]
            .textContent=
                size||'—';

        row.children[4]
            .textContent=
                time+' ms';


        networkList.appendChild(
            row
        );

    }


    const originalFetch=
        window.fetch;


    window.fetch=
        async function(){

            const start=
                performance.now();


            try{

                const response=
                    await originalFetch.apply(
                        this,
                        arguments
                    );


                const elapsed=
                    Math.round(
                        performance.now()-
                        start
                    );


                const request=
                    arguments[0];

                const url=
                    typeof request==='string'
                        ? request
                        : request.url;


                addNetworkEntry(
                    url,
                    response.status,
                    'fetch',
                    '',
                    elapsed
                );


                return response;

            }catch(error){

                const elapsed=
                    Math.round(
                        performance.now()-
                        start
                    );


                addNetworkEntry(
                    String(arguments[0]),
                    0,
                    'fetch',
                    '',
                    elapsed
                );


                throw error;

            }

        };


    /* =========================
       STORAGE
    ========================= */

    devtools
        .querySelectorAll(
            '[data-storage]'
        )
        .forEach(function(button){

            button.addEventListener(
                'click',
                function(){

                    showStorage(
                        button.dataset.storage
                    );

                }

            );

        });


    function showStorage(type){

        const container=
            document.getElementById(
                'ldt-storage-content'
            );

        if(!container)
            return;


        container.innerHTML='';


        if(type==='cookies'){

            const cookies=
                document.cookie;


            container.textContent=
                cookies ||
                'No cookies available.';

            return;

        }


        const storage=
            type==='local'
                ? localStorage
                : sessionStorage;


        const table=
            document.createElement(
                'table'
            );

        table.className=
            'ldt-storage-table';


        table.innerHTML=
            '<thead>'+
            '<tr>'+
            '<th>Key</th>'+
            '<th>Value</th>'+
            '</tr>'+
            '</thead>';


        const body=
            document.createElement(
                'tbody'
            );


        for(
            let i=0;
            i<storage.length;
            i++
        ){

            const key=
                storage.key(i);


            const row=
                document.createElement(
                    'tr'
                );


            const keyCell=
                document.createElement(
                    'td'
                );

            const valueCell=
                document.createElement(
                    'td'
                );


            keyCell.textContent=
                key;

            valueCell.textContent=
                storage.getItem(key);


            row.appendChild(
                keyCell
            );

            row.appendChild(
                valueCell
            );

            body.appendChild(
                row
            );

        }


        table.appendChild(
            body
        );

        container.appendChild(
            table
        );

    }


    /* =========================
       CLEAR NETWORK
    ========================= */

    document
        .getElementById(
            'ldt-clear-network'
        )
        .addEventListener(
            'click',
            function(){

                networkList.innerHTML='';

            }
        );


    /* =========================
       RELOAD
    ========================= */

    document
        .getElementById(
            'ldt-reload'
        )
        .addEventListener(
            'click',
            function(){

                location.reload();

            }
        );


    /* =========================
       BACK / FORWARD
    ========================= */

    document
        .getElementById(
            'ldt-back'
        )
        .addEventListener(
            'click',
            function(){

                history.back();

            }
        );


    document
        .getElementById(
            'ldt-forward'
        )
        .addEventListener(
            'click',
            function(){

                history.forward();

            }
        );


    /* =========================
       RESIZE
    ========================= */

    const resizer=
        document.getElementById(
            'labs-devtools-resizer'
        );


    let resizing=false;


    resizer.addEventListener(
        'mousedown',
        function(event){

            event.preventDefault();

            resizing=true;

            document.body.style.userSelect=
                'none';

        }
    );


    document.addEventListener(
        'mousemove',
        function(event){

            if(!resizing)
                return;


            const width=
                window.innerWidth-
                event.clientX;


            if(
                width>=500 &&
                width<=window.innerWidth-100
            ){

                devtools.style.width=
                    width+'px';

            }

        }
    );


    document.addEventListener(
        'mouseup',
        function(){

            if(!resizing)
                return;


            resizing=false;

            document.body.style.userSelect=
                '';

        }
    );


    /* =========================
       DOCK
    ========================= */

    dockButton.addEventListener(
        'click',
        function(){

            if(
                devtools.style.width
            ){

                devtools.style.width='';

            }else{

                devtools.style.width=
                    '100vw';

            }

        }
    );


    /* =========================
       MORE
    ========================= */

    document
        .getElementById(
            'ldt-more'
        )
        .addEventListener(
            'click',
            function(){

                alert(
                    'Labs DevTools\\n\\n' +
                    'F12 : ouvrir / fermer\\n' +
                    'Ctrl + Shift + I : ouvrir / fermer\\n' +
                    'Inspecteur : sélectionner un élément'
                );

            }
        );


})();

</script>