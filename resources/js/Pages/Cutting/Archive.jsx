import React, { useEffect, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { usePrevious } from 'react-use';
import { Head } from '@inertiajs/react';
import { Button, Dropdown } from 'flowbite-react';
import { HiArchive, HiArrowCircleLeft, HiFolderDownload, HiPencil, HiTrash } from 'react-icons/hi';
import { useModalState } from '@/hooks';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Pagination from '@/Components/Pagination';
import ModalConfirm from '@/Components/ModalConfirm';
import FormModal from './FormModal';
import SearchInput from '@/Components/SearchInput';
import { hasPermission } from '@/utils';

export default function Index(props) {
    const { query: { links, data }, auth } = props
    
    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const confirmModal = useModalState()
    const formModal = useModalState()

    const toggleFormModal = (cutting = null) => {
        formModal.setData(cutting)
        formModal.toggle()
    }

    const handleDeleteClick = (cutting) => {
        confirmModal.setData(cutting)
        confirmModal.toggle()
    }

    const onDelete = () => {
        if(confirmModal.data !== null) {
            router.delete(route('cutting.destroy', confirmModal.data.id))
        }
    }
    const UnArchive = (cutting) => {
        confirmModal.setData(cutting)
        confirmModal.toggle()
        
    }
    const onUnarchive=()=>{
        if (confirmModal.data !== null) {
            router.put(route('cutting.unarchive', confirmModal.data.id))
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

    const canUpdate = hasPermission(auth, 'update-cutting')
    const canDelete = hasPermission(auth, 'delete-cutting')
   
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Cutting'}
        >
            <Head title="Cutting"/>
            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-gray-200 dark:bg-gray-800 space-y-4">
                        <div className='flex justify-between'>
                        <Link href={route("cutting.index")} className="mr-3 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 flex space-x-3  items-center">
                                    <HiArrowCircleLeft />
                                    <span>
                                        Kembali
                                    </span>
                                </Link>
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
                                                Nama
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Total PO
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Hasil Cutting
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Sisa
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Konsumsi
                                            </th>
                                            <th scope="col" className="py-3 px-6"/>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {data.map(cutting => (
                                            <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={cutting.id}>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {cutting.name}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {
                                                    cutting.cutting_items.reduce((sum,val)=>
                                                       sum+=val.qty,0
                                                        )}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {cutting.result_quantity}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {cutting.fritter_quantity}
                                                </td>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {parseFloat(cutting.consumsion).toFixed(3)}
                                                </td>
                                                <td className="py-4 px-6 flex justify-end">
                                                    <Dropdown
                                                        label={"Opsi"}
                                                        floatingArrow={true}
                                                        arrowIcon={true}
                                                        dismissOnClick={true}
                                                        size={'sm'}
                                                    >
                                                        <Dropdown.Item onClick={() => UnArchive(cutting)}>
                                                                <div className='flex space-x-1 items-center'>
                                                                    <HiArchive />
                                                                    <div>Unarchive</div>
                                                                </div>
                                                            </Dropdown.Item>
                                                       
                                                            <Dropdown.Item>
                                                            <a href={route("cutting.export", cutting)} target="_blank" className="flex space-x-1 items-center">
                                                                <HiFolderDownload/> 
                                                                <div>Excel</div>
                                                            </a>
                                                        </Dropdown.Item>
                                                        
                                                        
                                                        {canUpdate && (
                                                            <Dropdown.Item>
                                                            <Link href={route("cutting.edit", cutting)} className="flex space-x-1 items-center">
                                                                <HiPencil/> 
                                                                <div>Ubah</div>
                                                            </Link>
                                                        </Dropdown.Item>
                                                        )}
                                                        {canDelete&&cutting.consumsion==0 && (
                                                            <Dropdown.Item onClick={() => handleDeleteClick(cutting)}>
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
             <ModalConfirm
                modalState={confirmModal}
                onConfirm={onUnarchive}
            />
            <FormModal
                modalState={formModal}
              
            />
        </AuthenticatedLayout>
    );
}