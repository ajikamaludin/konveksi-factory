import React, { useEffect, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { usePrevious } from 'react-use';
import { Head } from '@inertiajs/react';
import { Button, Dropdown } from 'flowbite-react';
import { HiPencil, HiTrash } from 'react-icons/hi';
import { useModalState } from '@/hooks';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import ModalConfirm from '@/Components/ModalConfirm';
import FormModal from './FormModal';
import SearchInput from '@/Components/SearchInput';
import { formatDate, hasPermission } from '@/utils';

export default function Index(props) {
    const { query: { links, data }, auth } = props
    
    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()
    const formModal = useModalState()

    const toggleFormModal = (production = null) => {
        formModal.setData(production)
        formModal.toggle()
    }

    const handleDeleteClick = (production) => {
        confirmModal.setData(production)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if(confirmModal.data !== null) {
            router.delete(route('production.destroy', confirmModal.data.id))
        }
    }

    const params = { q: search }
    useEffect(() => {
        if (preValue) {
            router.get(
                route(route().current()),
                { q: search },
                {
                    replace: true,
                    preserveState: true,
                }
            )
        }
    }, [search])

    const canCreate = hasPermission(auth, 'create-production')
    const canUpdate = hasPermission(auth, 'update-production')
    const canDelete = hasPermission(auth, 'delete-production')

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Artikel'}
        >
            <Head title="Artikel"/>

            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-gray-200 dark:bg-gray-800 space-y-4">
                        <div className='flex justify-between'>
                            {canCreate && (
                                <Link href={route("production.create")} className='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5'>Tambah</Link>
                            )}
                            <div className="flex items-center">
                                <SearchInput
                                    onChange={e => setSearch(e.target.value)}
                                    value={search}
                                />
                            </div>
                        </div>
                        <div className='overflow-auto'>
                            <div>
                                <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                                    <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" className="py-3 px-6">
                                                Kode
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Name
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Deadline
                                            </th>
                                            <th scope="col" className="py-3 px-6"/>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map(production => (
                                            <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={production.id}>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {production.code}
                                                </td>
                                                <td className="py-4 px-6">
                                                    {production.name}
                                                </td>
                                                <td className="py-4 px-6">
                                                    {formatDate(production.deadline)}
                                                </td>
                                                <td className="py-4 px-6 flex justify-end">
                                                    <Dropdown
                                                        label={"Opsi"}
                                                        floatingArrow={true}
                                                        arrowIcon={true}
                                                        dismissOnClick={true}
                                                        size={'sm'}
                                                    >
                                                        {canUpdate && (
                                                            <Dropdown.Item>
                                                                <Link href={route("production.edit", production)} className="flex space-x-1">
                                                                    <HiPencil/> 
                                                                    <div>Ubah</div>
                                                                </Link>
                                                            </Dropdown.Item>
                                                        )}
                                                        {canDelete && (
                                                            <Dropdown.Item onClick={() => handleDeleteClick(production)}>
                                                                <div className='flex space-x-1 items-center'>
                                                                    <HiTrash/> 
                                                                    <div>Hapus</div>
                                                                </div>
                                                            </Dropdown.Item>
                                                        )}
                                                    </Dropdown>
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                            <div className='w-full flex items-center justify-center'>
                                <Pagination links={links} params={params}/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <ModalConfirm
                modalState={confirmModal}
                onConfirm={onDelete}
            />
            <FormModal
                modalState={formModal}
            />
        </AuthenticatedLayout>
    );
}