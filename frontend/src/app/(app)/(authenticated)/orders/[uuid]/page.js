'use client'

import axios from '@/lib/axios'
import RenderHtml from "@/components/RenderHtml"
import EmailMessageEditor from "@/components/EmailMessageEditor"
import {useEffect, useState} from "react"
import {notFound} from "next/navigation"
import Loading from "@/components/Loading"

export default function Order({params}) {
    const [order, setOrder] = useState(null)
    const [isLoading, setLoading] = useState(true)
    const orderUuid = params.uuid

    function getOrderData() {
        axios
            .get(`/api/orders/get/${orderUuid}`)
            .then(response => {
                setOrder(response.data)
            })
            .catch(error => {
                if (error.response.status !== 404) notFound()
            })
            .finally(() => {
                setLoading(false)
            })
    }
    useEffect(() => getOrderData(), [orderUuid])

    return (
        <>
            {isLoading ? (
                <Loading/>
            ) : (
                <>
                    {order === null && <p>Order not found</p>}
                    {order !== null && (
                        <>
                            <div className="w-full">
                                <div className="flex flex-col md:flex-row bg-gray-800 text-white p-6 mx-2 my-1 rounded-lg">
                                    <div className="flex flex-col md:items-center overflow-x-auto md:overflow-x-hidden">
                                        <svg
                                            fill="none"
                                            stroke="white"
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            viewBox="0 0 24 24"
                                            className="w-8 h-8 text-gray-500">
                                            <path
                                                d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                        </svg>

                                        <div
                                            className="flex flex-col intems-center md:ml-4 text-lg leading-7 font-semibold">
                                            <small className="leading-tight text-gray-400">Order
                                                number</small>
                                            <strong className="text-xl">#{order.number}</strong>
                                        </div>
                                    </div>

                                    <div
                                        className="flex flex-col flex-auto mt-2 md:ml-12 text-sm overflow-x-auto md:overflow-x-hidden">
                                        <div>
                                            Customer name: <strong>{order.customer_name}</strong>
                                        </div>
                                        <div>
                                            Customer email: <strong>{order.customer_email}</strong>
                                        </div>
                                        <div>
                                            Number of emails: <strong>{order.emails_count}</strong>
                                        </div>
                                    </div>
                                </div>

                                {order.emails.map((email, index) => {
                                    return (
                                        <div
                                            className="flex flex-col bg-white p-6 mx-2 my-3 shadow-md rounded-lg"
                                            key={`email-${index}`}
                                        >
                                            <div
                                                className="relative flex flex-col lg:flex-row items-baseline lg:items-center bg-gray-100 p-6 rounded-lg rounded-">
                                                <svg
                                                    fill="none"
                                                    stroke="currentColor"
                                                    strokeLinecap="round"
                                                    strokeLinejoin="round"
                                                    strokeWidth="2"
                                                    viewBox="0 0 24 24"
                                                    className="absolute lg:static right-5 w-8 h-8 text-gray-500">
                                                    <path
                                                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                                                </svg>

                                                <div className="w-full mt-2 lg:ml-12 text-sm">
                                                    <div className="flex flex-col overflow-x-auto mr-10 lg:mr-0">
                                                        <div>
                                                            From: <strong>{email.from}</strong>
                                                        </div>
                                                        <div>
                                                            To: <strong>{email.to}</strong>
                                                        </div>
                                                        <div>
                                                            Date: <strong>{email.created_at}</strong>
                                                        </div>
                                                        {email?.attachments.length > 0 && (
                                                            <>
                                                                <div>Attachments:</div>
                                                                {email?.attachments.map((attachment, index) => {
                                                                    return (
                                                                        <a
                                                                            key={`${email.uuid}-${index}`}
                                                                            href={attachment.download_path}
                                                                            target="_blank"
                                                                            rel="noreferrer"
                                                                            className="block underline"
                                                                        >
                                                                            <strong>{attachment.file_name}</strong>
                                                                        </a>
                                                                    )
                                                                })}
                                                            </>
                                                        )}
                                                        <div>
                                                            Message:
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                            <div className="flex overflow-x-auto p-6 mx-2 mt-4 rounded-lg">
                                                <RenderHtml html={email.body}/>
                                            </div>
                                        </div>
                                    )
                                })}

                                <EmailMessageEditor
                                    sender={`${process.env.MAIL_FROM_ADDRESS}`}
                                    senderName={`${process.env.MAIL_FROM_NAME}`}
                                    recipient={order.customer_email}
                                    recipientName={order.customer_name}
                                    subject={`RE: ${order.emails[order.emails.length - 1].subject}`}
                                    inReplyTo={order.emails[order.emails.length - 1].messageId}
                                    getOrderData={getOrderData}
                                />
                            </div>
                        </>
                    )}
                </>
            )}
        </>
    )
}
