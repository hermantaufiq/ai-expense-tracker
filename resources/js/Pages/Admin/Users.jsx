import React from 'react';
import { usePage, router } from '@inertiajs/react';
import Sidebar from '../Components/Admin/Sidebar';
import Navbar from '../Components/Admin/Navbar';

export default function Users({ users }) {
    const { flash } = usePage().props;

    const toggleBan = (user) => {
        if (confirm(`Apakah Anda yakin ingin ${user.is_banned ? 'membuka blokir' : 'memblokir'} ${user.name}?`)) {
            router.post(route('admin.users.toggle-ban', user.id), {}, { preserveScroll: true });
        }
    };

    const togglePremium = (user) => {
        if (confirm(`Apakah Anda yakin ingin mengubah status Premium untuk ${user.name}?`)) {
            router.post(route('admin.users.toggle-premium', user.id), {}, { preserveScroll: true });
        }
    };

    return (
        <div className="min-h-screen bg-slate-50 flex">
            {/* Sidebar */}
            <Sidebar />

            {/* Main Content Area */}
            <div className="flex-1 ml-64 flex flex-col min-h-screen">
                {/* Navbar */}
                <Navbar />

                {/* Page Content */}
                <main className="flex-1 p-8">
                    <div className="flex items-center justify-between mb-8">
                        <div>
                            <h1 className="text-2xl font-bold text-slate-900">Manajemen Pengguna</h1>
                            <p className="text-slate-500 mt-1">Kelola data pengguna, akses, dan status premium.</p>
                        </div>
                    </div>

                    {flash?.success && (
                        <div className="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center gap-3">
                            <svg className="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span className="text-sm font-medium text-emerald-800">{flash.success}</span>
                        </div>
                    )}

                    <div className="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm text-slate-600">
                                <thead className="bg-slate-50 border-b border-slate-200 text-slate-700">
                                    <tr>
                                        <th className="px-6 py-4 font-semibold">Pengguna</th>
                                        <th className="px-6 py-4 font-semibold">Tipe Akun</th>
                                        <th className="px-6 py-4 font-semibold">Status</th>
                                        <th className="px-6 py-4 font-semibold">Tanggal Daftar</th>
                                        <th className="px-6 py-4 font-semibold text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {users.length > 0 ? (
                                        users.map((user) => (
                                            <tr key={user.id} className="hover:bg-slate-50/50 transition-colors">
                                                <td className="px-6 py-4">
                                                    <div className="flex items-center gap-3">
                                                        {user.avatar ? (
                                                            <img src={`/storage/${user.avatar}`} alt="" className="w-10 h-10 rounded-full object-cover" />
                                                        ) : (
                                                            <div className="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                                                {user.name.charAt(0)}
                                                            </div>
                                                        )}
                                                        <div>
                                                            <div className="font-semibold text-slate-900">{user.name}</div>
                                                            <div className="text-xs text-slate-500">{user.email}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td className="px-6 py-4">
                                                    {user.is_premium ? (
                                                        <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800 border border-amber-200">
                                                            <svg className="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zM9.5 4a1.5 1.5 0 110 3 1.5 1.5 0 010-3zm-1 4h2v5h-2V8z" clipRule="evenodd"></path></svg>
                                                            Premium
                                                        </span>
                                                    ) : (
                                                        <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-slate-100 text-slate-700 border border-slate-200">
                                                            Free
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4">
                                                    {user.is_banned ? (
                                                        <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700 border border-red-200">
                                                            Terblokir
                                                        </span>
                                                    ) : (
                                                        <span className="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                            Aktif
                                                        </span>
                                                    )}
                                                </td>
                                                <td className="px-6 py-4">
                                                    {new Date(user.created_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}
                                                </td>
                                                <td className="px-6 py-4 text-right">
                                                    <div className="flex justify-end gap-2">
                                                        <button 
                                                            onClick={() => togglePremium(user)}
                                                            className={`p-2 rounded-lg transition-colors ${user.is_premium ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200'}`}
                                                            title={user.is_premium ? "Downgrade ke Free" : "Upgrade ke Premium"}
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                                        </button>
                                                        <button 
                                                            onClick={() => toggleBan(user)}
                                                            className={`p-2 rounded-lg transition-colors ${user.is_banned ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-700 hover:bg-red-200'}`}
                                                            title={user.is_banned ? "Buka Blokir" : "Blokir Pengguna"}
                                                        >
                                                            <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                {user.is_banned ? (
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                                                ) : (
                                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                                )}
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        ))
                                    ) : (
                                        <tr>
                                            <td colSpan="5" className="px-6 py-8 text-center text-slate-500">
                                                Belum ada pengguna terdaftar.
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    );
}
