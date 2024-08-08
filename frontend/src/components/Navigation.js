'use client'

import Dropdown from '@/components/Dropdown'
import NavLink from '@/components/NavLink'
import ResponsiveNavLink, {
    ResponsiveNavButton,
} from '@/components/ResponsiveNavLink'
import {DropdownButton} from '@/components/DropdownLink'
import {useAuth} from '@/hooks/auth'
import {usePathname} from 'next/navigation'
import {useState} from 'react'

export default function Navigation() {
    const {user, logout} = useAuth({middleware: 'guest'})
    const [open, setOpen] = useState(false)

    return (
        <nav className="flex flex-auto">
            {/* Primary Navigation Menu */}
            <div className="flex justify-end w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex h-16">
                    {user ? (
                        <>
                            {/* Authenticated User */}
                            <div className="flex">
                                {/* Navigation Links */}
                                <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink
                                        href="/orders"
                                        active={usePathname().includes('/orders')}>
                                        Orders
                                    </NavLink>
                                </div>
                            </div>

                            {/* Settings Dropdown */}
                            <Dropdown
                                align="right"
                                width="48"
                                trigger={
                                    <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                        <button
                                            className="pt-1 px-1 flex items-center text-sm font-medium text-white border-b-2 border-transparent hover:border-white">
                                            <div>{user?.name}</div>

                                            <div className="ml-1">
                                                <svg
                                                    className="fill-current h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        fillRule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clipRule="evenodd"
                                                    />
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                }>
                                {/* Authentication */}
                                <DropdownButton onClick={logout}>
                                    Logout
                                </DropdownButton>
                            </Dropdown>
                        </>
                    ) : (
                        <>
                            {/* Guest User */}
                            <div className="flex">
                                {/* Navigation Links */}
                                <div className="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                    <NavLink
                                        href="/login"
                                        active={usePathname().includes('/login')}>
                                        Login
                                    </NavLink>
                                    <NavLink
                                        href="/register"
                                        active={usePathname().includes('/register')}>
                                        Register
                                    </NavLink>
                                </div>
                            </div>
                        </>
                    )}


                    {/* Hamburger */}
                    <div className="flex sm:hidden">
                        <button
                            onClick={() => setOpen(open => !open)}
                            className="text-white">
                            <svg
                                className="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24">
                                {open ? (
                                    <path
                                        className="inline-flex"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                ) : (
                                    <path
                                        className="inline-flex"
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                )}
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {/* Responsive Navigation Menu */}
            {open && (
                <div className="fixed top-24 right-12 bg-white min-w-72 py-4 shadow-md rounded-md sm:hidden">
                    {user ? (
                        <>
                            {/* Authenticated User */}
                            <div className="pb-1">
                                {/* Responsive Settings Options */}
                                <div className="flex items-center px-4">
                                    <div className="flex-shrink-0">
                                        <svg
                                            className="h-10 w-10 fill-current text-gray-400"
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                            />
                                        </svg>
                                    </div>

                                    <div className="ml-3">
                                        <div className="font-medium text-base text-gray-800">
                                            {user?.name}
                                        </div>
                                        <div className="font-medium text-sm text-gray-500">
                                            {user?.email}
                                        </div>
                                    </div>
                                </div>

                                <div className="mt-3 space-y-1">
                                    {/* Navigation Links */}
                                    <ResponsiveNavLink
                                        href="/orders"
                                        active={usePathname().includes('/orders')}>
                                        Orders
                                    </ResponsiveNavLink>
                                    {/* Authentication */}
                                    <ResponsiveNavButton onClick={logout}>
                                        Logout
                                    </ResponsiveNavButton>
                                </div>
                            </div>
                        </>
                    ) : (
                        <>
                            {/* Guest User */}
                            <div className="pt-2 pb-3 space-y-1">
                                {/* Navigation Links */}
                                <ResponsiveNavLink
                                    href="/login"
                                    active={usePathname().includes('/login')}>
                                    Login
                                </ResponsiveNavLink>
                                <ResponsiveNavLink
                                    href="/register"
                                    active={usePathname().includes('/register')}>
                                    Register
                                </ResponsiveNavLink>
                            </div>
                        </>
                    )}

                </div>
            )}
        </nav>
    )
}
