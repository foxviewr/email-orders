import {NunitoFont} from '@/lib/fonts'
import '@/app/global.css'
import Header from "@/app/Header";
import Footer from "@/app/Footer";

export default function RootLayout({children}) {
    return (
        <html lang="en" className={NunitoFont.className}>
        <body className="antialiased">
        <div
            className="relative flex items-top justify-center min-h-screen bg-rose-600 sm:items-top sm:pt-0">
            <div className="flex flex-col py-8 w-full max-w-7xl mx-auto px-8">
                <Header/>
                <div
                    className="flex sm:flex-grow w-full p-4 sm:px-10 sm:py-6 mt-8 bg-gray-100 overflow-hidden shadow-md rounded-lg">
                    {children}
                </div>
                <Footer/>
            </div>
        </div>
        </body>
        </html>
    )
}
