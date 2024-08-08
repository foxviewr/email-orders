import Link from 'next/link'

export default function NavLink({active = false, children, ...props}) {
    return (
        <Link
            {...props}
            className={`inline-flex items-center text-white px-1 pt-1 border-b-2 text-sm font-medium leading-5 ${
                active
                    ? 'border-white font-extrabold'
                    : 'border-transparent hover:border-white'
            }`}>
            {children}
        </Link>
    )
}
