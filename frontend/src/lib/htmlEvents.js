export const InlineHtmlEventsList = [
    'onabort',
    'onafterprint',
    'onbeforeprint',
    'onbeforeunload',
    'onblur',
    'oncanplay',
    'oncanplaythrough',
    'onchange',
    'onclick',
    'oncopy',
    'oncut',
    'ondblclick',
    'ondrag',
    'ondragend',
    'ondragenter',
    'ondragleave',
    'ondragover',
    'ondragstart',
    'ondrop',
    'ondurationchange',
    'onended',
    'onerror',
    'onfocus',
    'onfocusin',
    'onfocusout',
    'onhashchange',
    'oninput',
    'oninvalid',
    'onkeydown',
    'onkeypress',
    'onkeyup',
    'onload',
    'onloadeddata',
    'onloadedmetadata',
    'onloadstart',
    'onmousedown',
    'onmouseenter',
    'onmouseleave',
    'onmousemove',
    'onmouseover',
    'onmouseout',
    'onmouseup',
    'onmousewheel',
    'onoffline',
    'ononline',
    'onpagehide',
    'onpageshow',
    'onpaste',
    'onpause',
    'onplay',
    'onplaying',
    'onpopstate',
    'onprogress',
    'onratechange',
    'onresize',
    'onreset',
    'onscroll',
    'onsearch',
    'onseeked',
    'onseeking',
    'onselect',
    'onshow',
    'onstalled',
    'onstorage',
    'onsubmit',
    'onsuspend',
    'ontimeupdate',
    'ontoggle',
    'ontouchcancel',
    'ontouchend',
    'ontouchmove',
    'ontouchstart',
    'onunload',
    'onvolumechange',
    'onwaiting',
];


export function getInlineHtmlEventsListForQueryAll () {
    let query = '';
    InlineHtmlEventsList.forEach((htmlEvent, index) => {
        query += `*[${htmlEvent}]`;
        if (index < InlineHtmlEventsList.length - 1) {
            query += ', ';
        }
    });
    return query;
}

export function stripAwayAllInlineHtmlEvents (htmlElement) {
    htmlElement.querySelectorAll(getInlineHtmlEventsListForQueryAll()).forEach((node) => {
        InlineHtmlEventsList.forEach((inlineHtmlEvent) => {
            if (node.hasAttribute(inlineHtmlEvent)) {
                node.removeAttribute(inlineHtmlEvent);
            }
        });
    });
}

export default InlineHtmlEventsList;
