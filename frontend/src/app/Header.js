import {Space_GroteskFont} from '@/lib/fonts'
import Navigation from "@/components/Navigation";

export default function Header() {
    return (
        <div className="flex items-center">
            <a
                className={`${Space_GroteskFont.className} flex flex-col items-center justify-center pt-0 sm:justify-start`}
                href="/"
                aria-label="Home"
            >
                <svg viewBox="0 -1 24 26"
                     fill="white"
                     xmlns="http://www.w3.org/2000/svg"
                     className="h-16 w-auto text-gray-700 sm:h-20">
                    <path
                        d="M21.971 8.92 17.769 7.3a.75.75 0 1 0-.539 1.4l2.168.835a.25.25 0 0 1 0 .466l-7.309 2.924a.257.257 0 0 1-.185 0L4.569 9.989a.249.249 0 0 1 0-.466L6.763 8.7a.75.75 0 1 0-.527-1.4L1.978 8.9a.769.769 0 0 0-.479.72v8.917a.5.5 0 0 0 .277.447l10 4.969h.014a.472.472 0 0 0 .419 0h.014l10-4.969a.5.5 0 0 0 .276-.447V9.5c.001-.012.048-.359-.528-.58Z"></path>
                    <path
                        d="M11.2 10.1a1 1 0 0 0 1.6 0l3-4a1 1 0 0 0-.8-1.6h-1.5v-3a1.5 1.5 0 0 0-3 0v3H9a1 1 0 0 0-.8 1.6Z"></path>
                </svg>
                <p className="ml-3 text-2xl uppercase text-white">Email Orders</p>
            </a>
            <Navigation/>
        </div>
    )
}
