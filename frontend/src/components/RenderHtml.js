import {parse} from 'node-html-parser'
import {stripAwayAllInlineHtmlEvents} from "@/lib/htmlEvents"

export function parseHtml(html) {
    const element = parse(html)

    stripAwayAllInlineHtmlEvents(element)
    element.querySelectorAll('iframe, form, script').forEach((node) => {
        node.remove()
    })
    if (element.getElementsByTagName('body').length) {
        return element.getElementsByTagName('body')[0].innerHTML
    }

    return element.innerHTML
}

export function RenderHtml({html}) {
    const markup = {__html: parseHtml(html)}
    return <div dangerouslySetInnerHTML={markup}/>
}

export default RenderHtml
