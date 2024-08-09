'use client'

import axios from '@/lib/axios'
import PageTitle from "@/components/PageTitle"
import {useEffect, useState} from "react";
import Loading from "@/components/Loading";

export default function Orders() {
    const [orders, setOrders] = useState([])
    const [isLoading, setLoading] = useState(true)

    useEffect(() => {
        axios
            .get('/api/orders/get/all')
            .then(response => {
                setOrders(response.data)
            })
            .catch(error => {
                if (error.response.status !== 404) throw error
            })
            .finally(() => {
                setLoading(false)
            })
    }, [])

    return (
        <>
            {isLoading ? (
                <Loading/>
            ) : (
                <>
                    <div className="w-full">
                        <PageTitle>Orders</PageTitle>
                        <div className="flex flex-col mt-6">
                            {!orders.length && <p>No orders found.</p>}
                            {orders.length > 0 && orders.map((order, index) => {
                                return (
                                    <a
                                        className="relative md:static flex flex-col md:flex-row bg-white p-6 pr-12 mx-2 my-1 cursor-pointer rounded-lg hover:bg-gray-200"
                                        href={`/orders/${order.uuid}`}
                                        key={index}
                                    >
                                        <div className="flex flex-col md:flex-row items-start md:items-center overflow-x-auto md:overflow-x-hidden">
                                            <svg
                                                fill="none"
                                                stroke="currentColor"
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                viewBox="0 0 24 24"
                                                className="w-8 h-8 text-gray-500">
                                                <path
                                                    d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                            </svg>

                                            <div
                                                className="flex flex-col md:ml-4 text-lg leading-7 font-semibold">
                                                <small className="leading-tight text-gray-600">Order number</small>
                                                <strong className="text-xl">#{order.number}</strong>
                                            </div>
                                        </div>

                                        <div
                                            className="text-sm md:pl-5 md:flex-grow overflow-x-auto md:overflow-x-hidden">
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

                                        <div className="absolute md:static right-3 top-0 bottom-0 flex items-center justify-end">
                                            <svg
                                                fill="none"
                                                stroke="currentColor"
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                viewBox="0 0 24 24"
                                                className="w-8 h-8 text-gray-500">
                                                <path d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                            </svg>
                                        </div>
                                    </a>
                                )
                            })}
                        </div>
                    </div>
                </>
            )}
        </>
    )
}
