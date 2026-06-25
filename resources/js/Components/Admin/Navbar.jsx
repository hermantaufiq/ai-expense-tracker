import React from 'react';

export default function Navbar() {
    return (
        <header className="h-16 bg-white/80 backdrop-blur-md border-b border-slate-200 shadow-sm flex items-center justify-between px-6 sticky top-0 z-10 transition-all duration-300">
            <div className="flex items-center gap-4">
                <div className="relative">
                    <svg className="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" placeholder="Search..." className="pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 w-64 transition-all" />
                </div>
            </div>
            
            <div className="flex items-center gap-4">
                <button className="relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span className="absolute top-1.5 right-1.5 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
                </button>
                
                <div className="h-8 w-px bg-slate-200"></div>
                
                <div className="flex items-center gap-3 cursor-pointer">
                    <div className="text-right hidden md:block">
                        <div className="text-sm font-semibold text-slate-900">Admin User</div>
                        <div className="text-xs text-slate-500">Superadmin</div>
                    </div>
                    <div className="h-9 w-9 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border border-indigo-200">
                        A
                    </div>
                </div>
            </div>
        </header>
    );
}
