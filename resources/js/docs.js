import hljs from 'highlight.js/lib/core';
import bash from 'highlight.js/lib/languages/bash';
import css from 'highlight.js/lib/languages/css';
import javascript from 'highlight.js/lib/languages/javascript';
import json from 'highlight.js/lib/languages/json';
import php from 'highlight.js/lib/languages/php';
import plaintext from 'highlight.js/lib/languages/plaintext';
import xml from 'highlight.js/lib/languages/xml';

//  Teach the highlighter the languages the docs show, and nothing else
hljs.registerLanguage('bash', bash);
hljs.registerLanguage('css', css);
hljs.registerLanguage('javascript', javascript);
hljs.registerLanguage('js', javascript);
hljs.registerLanguage('json', json);
hljs.registerLanguage('php', php);
hljs.registerLanguage('text', plaintext);
hljs.registerLanguage('xml', xml);

//  Other scripts of a page can use it too
window.hljs = hljs;

/**
 * Colour every code block
 */
function highlightCode() {
    document.querySelectorAll('pre code').forEach((block) => hljs.highlightElement(block));
}

/**
 * Copy text to the clipboard, also on a page that is not served over https, where the clipboard api does not exist
 */
function copyText(text) {
    //  The clipboard api, where the page is allowed to use it
    if (navigator.clipboard && window.isSecureContext) {
        return navigator.clipboard.writeText(text);
    }

    //  Otherwise select the text in a hidden field and copy it as a browser always could
    const field = document.createElement('textarea');
    field.value = text;
    field.setAttribute('readonly', '');
    field.style.position = 'fixed';
    field.style.opacity = '0';
    document.body.appendChild(field);
    field.select();

    //  Copy it, then remove the field
    const copied = document.execCommand('copy');
    field.remove();

    return copied ? Promise.resolve() : Promise.reject(new Error('The browser did not copy.'));
}

//  The playground copies JSON the same way
window.docsCopy = copyText;

/**
 * Copy the code of a block when its button is pressed, and say so on the button
 */
function listenForCopy() {
    /** Listen for a press on a copy button */
    document.addEventListener('click', (event) => {
        //  Only the copy buttons
        const button = event.target.closest('[data-docs-copy]');
        if (! button) return;

        //  Copy the code of the block the button sits in
        copyText(button.closest('figure').querySelector('code').innerText)
            .then(() => {
                //  Say it was copied
                button.textContent = 'Copied';

                //  Go back to the label a moment later
                setTimeout(() => (button.textContent = 'Copy'), 1500);
            });
    });
}

/**
 * Open and close the sidebar, which is a drawer on small screens
 */
function listenForMenu() {
    //  The sidebar
    const sidebar = document.querySelector('[data-docs-sidebar]');

    //  Open and close the sidebar
    document
        .querySelector('[data-docs-menu]')
        .addEventListener('click', () => sidebar.classList.toggle('hidden'));
}

/**
 * Filter the sidebar links by what the reader types, hiding the groups left empty
 */
function listenForFilter() {
    document.querySelector('[data-docs-filter]').addEventListener('input', (event) => {
        //  What the reader typed
        const term = event.target.value.toLowerCase();

        //  Hide the links that do not contain it
        document
            .querySelectorAll('[data-docs-link]')
            .forEach((item) => item.classList.toggle('hidden', ! item.textContent.toLowerCase().includes(term)));

        //  Hide the groups left without a link
        document
            .querySelectorAll('[data-docs-group]')
            .forEach((group) => group.classList.toggle('hidden', ! group.querySelector('[data-docs-link]:not(.hidden)')));
    });
}

/**
 * List the sections of the page in the "On this page" column
 */
function listSections() {
    //  The list to fill
    const toc = document.querySelector('[data-docs-toc]');

    //  Add a link for every section
    document.querySelectorAll('[data-docs-section]').forEach((section) => {
        //  The list item and its link
        const item = document.createElement('li');

        //  Get the inner html
        item.innerHTML = '<a class="-ml-px block border-l border-transparent py-0.5 pl-3 text-gray-500 hover:border-primary hover:text-primary-dark"></a>';

        //  Point the link at the section and name it by the heading
        item.firstChild.href = '#' + section.id;

        //  Define the text content
        item.firstChild.textContent = section.querySelector('h2').firstChild.textContent.trim();

        //  Add it to the list
        toc.appendChild(item);
    });
}

/** Set the page up once it is ready */
document.addEventListener('DOMContentLoaded', () => {
    //  Colour the code and wire the page controls
    highlightCode();

    //  Listen for copy
    listenForCopy();

    //  Listen for the menu
    listenForMenu();

    //  Listen for the filter
    listenForFilter();

    //  List the sections of the page
    listSections();
});
