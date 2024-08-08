import AuthCard from '@/app/(auth)/AuthCard'

export default function Layout({children}) {
    return (
        <div className="flex flex-grow sm:justify-center items-center sm:pt-0">
            <AuthCard>{children}</AuthCard>
        </div>
    )
}
