import axios from '@/lib/axios'
import PageTitle from "@/components/PageTitle";

async function getAllOrders() {
    try {
        const response = await axios.get('/api/orders/get/all');
        return response.data ?? [];
    } catch (error) {
        throw error;
    }
}

export default async function Orders() {
    const orders = await getAllOrders();

    return (
        <div className="w-full">
            <PageTitle>Orders</PageTitle>
            <div className="flex flex-col mt-3">
                {!orders.length && <p>No orders found</p>}
                {orders.length > 0 && orders.map((order, index) => {
                    return (
                        <a
                            className="grid grid-cols-12 gap-1 bg-white p-6 mx-2 my-1 cursor-pointer rounded-lg hover:bg-gray-200"
                            href={`/orders/${order.uuid}`}
                            key={index}
                        >
                            <div className="flex col-span-3 items-center">
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
                                    className="flex flex-col intems-center ml-4 text-lg leading-7 font-semibold">
                                    <small className="leading-tight text-gray-600">Order number</small>
                                    <strong className="text-xl">#{order.number}</strong>
                                </div>
                            </div>

                            <div
                                className="col-span-7 text-sm">
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

                            <div className="flex col-span-2 items-center justify-end">
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
    )
}
