import Link from 'next/link'

function ResponsiveNavLink({active = false, children, ...props}) {
    return (
        <Link
            {...props}
            className={`block pl-3 pr-4 py-2 border-l-4 text-base font-medium leading-5 ${
                active
                    ? 'border-rose-400 text-rose-700 bg-rose-50'
                    : 'border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300'
            }`}>
            {children}
        </Link>
    )
}

export function ResponsiveNavButton({...props}) {
    return (
        <button
            className="block w-full pl-3 pr-4 py-2 border-l-4 text-left text-base font-medium leading-5 focus:outline-none transition duration-150 ease-in-out border-transparent text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300"
            {...props}
        />
    )
}

export default ResponsiveNavLink
