'use client'

import axios from "@/lib/axios"
import {useState} from 'react'
import {useRouter} from 'next/navigation'
import {parseHtml} from "@/components/RenderHtml"

export default function EmailMessageEditor({
                                               sender,
                                               senderName,
                                               recipient,
                                               recipientName,
                                               subject,
                                               inReplyTo,
                                           }) {
    const router = useRouter()
    const [replyMessage, setReplyMessage] = useState('')
    const [loading, setLoading] = useState(false)
    const [errorMessage, setErrorMessage] = useState(null)

    async function onSubmit(event) {
        setLoading(true)
        try {
            event.preventDefault()
            const response = await axios.post(`/api/mailgun/send-reply`, {
                sender: sender,
                senderName: senderName,
                recipient: recipient,
                recipientName: recipientName,
                subject: subject,
                'body-html': parseHtml(replyMessage),
                'In-Reply-To': inReplyTo,
                'Message-Id': '----------------',
            })

            router.refresh()
            setReplyMessage('')
            setLoading(false)
            setErrorMessage(null)

            return response.data
        } catch (error) {
            setLoading(false)
            setErrorMessage(error.message)
            throw error
        }
    }

    return (
        <form
            className="flex flex-col bg-gray-800 px-6 py-4 mx-2 my-1 rounded-lg"
            onSubmit={onSubmit}
        >
            <div className="flex">
                <div className="flex flex-none items-center">
                    <svg
                        fill="none"
                        stroke="white"
                        strokeLinecap="round"
                        strokeLinejoin="round"
                        strokeWidth="2"
                        viewBox="0 0 24 24"
                        className="w-8 h-8 text-gray-500">
                        <path
                            d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                    </svg>
                </div>
                <div className="flex flex-auto justify-end mt-2 ml-12 text-sm">
                    <textarea
                        id="email_answer"
                        name="email_answer"
                        rows="4"
                        disabled={loading}
                        onChange={event => setReplyMessage(event.target.value)}
                        value={replyMessage}
                        className="p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required
                        placeholder="Write your answer here..."/>
                </div>
            </div>
            <div className="flex mt-2 justify-end">
                {errorMessage && (
                    <p className="text-red-500">{errorMessage}</p>
                )}
            </div>
            <div className="flex mt-2 justify-end">
                <button type="submit"
                        disabled={loading}
                        className={`${loading ? 'bg-gray-600' : 'bg-rose-600 hover:bg-rose-700'} focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm sm:w-32 items-center px-5 py-2.5 text-center text-white`}>
                    {loading ? 'Sending...' : 'Reply'}
                </button>
            </div>
        </form>
    )
}
