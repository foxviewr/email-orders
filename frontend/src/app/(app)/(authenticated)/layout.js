'use client'

import {useAuth} from '@/hooks/auth'
import Loading from '@/components/Loading'

export default function AppLayout({children}) {
    const {user} = useAuth({middleware: 'auth'})

    if (!user) {
        return <Loading/>
    }

    return (
        <div className="w-full">
            {children}
        </div>
    )
}
