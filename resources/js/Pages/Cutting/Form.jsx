import React, { useEffect } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { HiLockClosed, HiXCircle } from 'react-icons/hi';
import { isEmpty } from 'lodash';

import { useModalState } from '@/hooks';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Button from '@/Components/Button';
import FormInput from '@/Components/FormInput';
import FormInputDate from '@/Components/FormInputDate';
import BrandSelectionInput from '../Brand/SelectionInput'; 
import BuyerSelectionInput from '../Buyer/SelectionInput';
import MaterialSelectionInput from '../Material/SelectionInput';
import FormModal from './FormModal';

export default function Form(props) {
    const { cutting } = props

    const {data, setData, post, put, processing, errors} = useForm({
        style:'',
        buyer_id: '',
        brand_id: '',
        material_id: '',
        consumsion: '',
        deadline: '',
        name: '',
        items: [],
    })

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }

    const formItemModal = useModalState()

    const onItemAdd = (item) => {
        setData("items", data.items.concat(item))
    }

    const onItemRemove = (index) => {
        setData("items", data.items.filter((it, i) => i !== index))
    }

    const handleSubmit = () => {
        if(isEmpty(cutting) === false) {
            put(route('cutting.update', cutting))
            return
        }
        post(route('cutting.store'))
    }

    useEffect(() => {
        if(isEmpty(cutting) === false) {
           
            setData({
                style: cutting.style,
                name: cutting.name,
                buyer_id: cutting.buyer_id,
                brand_id: cutting.brand_id,
                material_id: cutting.material_id,
                description: cutting.description,
                deadline: cutting.deadline,
                consumsion:cutting.consumsion,
                items: cutting.cutting_items,
            })
        }
    }, [cutting]) 

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={"Dashboard"}
            action={"Cutting"}
        >
            <Head title={"Cutting"} />

            <div>
                <div className="mx-auto sm:px-6 lg:px-8">
                    <div className="overflow-hidden p-4 shadow-sm sm:rounded-lg bg-white dark:bg-gray-800 flex flex-col ">
                        <div className='text-xl font-bold mb-4'>Cutting</div>
                        <FormInput
                            name="style"
                            value={data.style}
                            onChange={handleOnChange}
                            label="Style"
                            error={errors.style}
                        />
                        <FormInput
                            name="name"
                            value={data.name}
                            onChange={handleOnChange}
                            label="Nama"
                            error={errors.name}
                        />
                        <FormInputDate
                            name="deadline"
                            selected={data.deadline}
                            onChange={date => setData("deadline", date)}
                            label="Deadline"
                            error={errors.deadline}
                        />
                        <div className='mb-2'>
                            <BrandSelectionInput
                                label="Brand"
                                itemSelected={data.brand_id}
                                onItemSelected={(id) => setData('brand_id', id)}
                                error={errors.brand_id}
                            />
                        </div>
                        <div className='mb-2'>
                            <BuyerSelectionInput
                                label="Pembeli"
                                itemSelected={data.buyer_id}
                                onItemSelected={(id) => setData('buyer_id', id)}
                                error={errors.buyer_id}
                            />
                        </div>
                        <div className='mb-2'>
                            <MaterialSelectionInput
                                label="Bahan"
                                itemSelected={data.material_id}
                                onItemSelected={(id) => setData('material_id', id)}
                                error={errors.material_id}
                            />
                        </div>
                       
                        <label>Size</label>
                        <div className='w-full flex flex-col border-2 rounded-lg p-2'>
                            <div className='mb-2'>
                                <button 
                                    type="button" 
                                    className="px-3 py-2 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300"
                                    onClick={formItemModal.toggle}
                                >
                                        Tambah
                                </button>
                            </div>
                            <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                                <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                    <th scope="col" className="py-3 px-6">
                                            Color
                                        </th>
                                        <th scope="col" className="py-3 px-6">
                                            Size
                                        </th>
                                        <th scope="col" className="py-3 px-6">
                                            Quantity
                                        </th>
                                        
                                        <th scope="col" className="py-3 px-6"/>
                                    </tr>
                                </thead>
                                <tbody>
                                    {data.items.map((item, index) => (
                                        <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={index}>
                                           <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {item.color.name}
                                            </td>
                                            <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                {item.size.name}
                                            </td>
                                            <td className="py-4 px-6">
                                                {item.qty}
                                            </td>
                                            <td>
                                                    <HiXCircle className="w-5 h-5 text-red-600" onClick={() => onItemRemove(index)}/>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>

                        <div className='mt-10'>
                            <Button
                                onClick={handleSubmit}
                                processing={processing} 
                            >
                                Simpan
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
            <FormModal
                modalState={formItemModal}
                onItemAdd={onItemAdd}
            />
        </AuthenticatedLayout>
    );
}