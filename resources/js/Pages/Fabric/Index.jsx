import React, { useEffect, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { usePrevious } from 'react-use';
import { Head } from '@inertiajs/react';
import {  Dropdown } from 'flowbite-react';
import { useModalState } from '@/hooks';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import ModalConfirm from '@/Components/ModalConfirm';
import SearchInput from '@/Components/SearchInput';
import { hasPermission } from '@/utils';
import { HiPencil,HiTrash } from 'react-icons/hi';

export default function Index(props) {
    const { query: { links, data }, auth } = props
    
    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()

    const handleDeleteClick = (fabric) => {
        confirmModal.setData(fabric)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if(confirmModal.data !== null) {
            router.delete(route('fabric.destroy', confirmModal.data.id))
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

    const canCreate = hasPermission(auth, 'create-fabric')
    const canUpdate = hasPermission(auth, 'update-fabric')
    const canDelete = hasPermission(auth, 'delete-fabric')
    
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Kain'}
        >
            <Head title="Kain"/>

            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-gray-200 dark:bg-gray-800 space-y-4">
                        <div className='flex justify-between'>
                            {canCreate && (
                                <Link href={route("fabric.create")} className='text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5'>Tambah</Link>
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
                                                #
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Nama
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Supplier
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Total Kg
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Sisa
                                            </th>
                                            <th scope="col" className="py-3 px-6"/>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map((fabric,index) => (
                                            <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={fabric.id}>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {index+1}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {fabric.name}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {fabric.supplier?.name}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {fabric.qty}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {fabric.fritter_qty}
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
                                                                <Link href={route("fabric.edit", fabric)} className="flex space-x-1 items-center">
                                                                    <HiPencil/> 
                                                                    <div>Ubah</div>
                                                                </Link>
                                                            </Dropdown.Item>
                                                        )}
                                                        {canDelete && fabric.result_qty==0 && (
                                                            <Dropdown.Item onClick={() => handleDeleteClick(fabric)}>
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
          
        </AuthenticatedLayout>
    );
}