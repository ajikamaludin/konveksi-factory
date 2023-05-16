import React, { useState, useEffect } from 'react';
import { router, Head, useForm } from '@inertiajs/react';
import { usePrevious } from 'react-use';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProductionSelectionInput from '../Production/SelectionInput';
import ColorSelectionInput from '../Color/SelectionInput';
import SizeSelectionInput from '../Size/SelectionInput';
import { isEmpty } from 'lodash';
import { HiOutlinePlusCircle } from 'react-icons/hi';
import FormInput from '@/Components/FormInput';
import Input from '@/Components/Input';

export default function Index(props) {
    const { item, _production, _color, _size,operator } = props

    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
        finish_quantity: 0,
        reject_quantity: 0,
        qty: 0,
    })

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }

    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)

    const [buyer, setBuyer] = useState('-')
    const [production, setProduction] = useState(_production)
    const [color, setColor] = useState(_color)
    const [size, setSize] = useState(_size)

    const handleSelectProduction = (production) => {
        if (isEmpty(production) === false) {
            setBuyer(production?.buyer?.name)
            setProduction(production)
            setSearch({ ...search, production_id: production.id })
            setData('qty',operator)
            return
        }
        setSearch({ ...search, production_id: '' })
        setBuyer('-')
        setProduction('')
    }

    const handleSelectColor = (color) => {
        if (isEmpty(color) === false) {
            setColor(color)
            setSearch({ ...search, color_id: color.id })
            setData('qty',operator)
            return
        }
        setColor('')
        setSearch({ ...search, color_id: '' })
    }

    const handleSize = (size) => {
        if (isEmpty(size) === false) {
            setSize(size)
            setSearch({ ...search, size_id: size.id })
            setData('qty',operator)
            return
        }
        setSearch({ ...search, size_id: '' })
        setSize('')
        
    }

    const handleReset = () => {
        reset()
    }

    const addQuantity = () => {
        setData('finish_quantity', +data.finish_quantity + 1)
    }

    const addReject = () => {
        setData('reject_quantity', +data.reject_quantity + 1)
    }

    const handleSubmit = () => {
        post(route('line.sewing.create', item), {
            onSuccess: () => handleReset()
        })
    }

    useEffect(() => {
        if (preValue) {
            router.get(
                route(route().current()),
                search,
                {
                    replace: true,
                    preserveState: true,
                }
            )
            setData({
                finish_quantity: 0,
                reject_quantity: 0,
                qty:operator,
            })
        }
    }, [search])

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Line Sewing'}
        >
            <Head title="Line Sewing" />

            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white space-y-6 min-h-screen">
                        <div className='grid grid-cols-2 text-center'>
                            <div className='border-x-2'>
                                <div className='mb-2'>Buyer</div>
                                <div className='font-bold'>{buyer}</div>
                            </div>
                            <div className='border-r-2 px-2'>
                                <div className='mb-2'>Quantity</div>
                                <div className='font-bold'>{item?.target_quantity}</div>
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            
                            <div className='border-x-2 px-2'>
                                <div className='mb-2'>Artikel</div>
                                <ProductionSelectionInput
                                    itemSelected={production?.id}
                                    onItemSelected={(item) => handleSelectProduction(item)}
                                />
                            </div>
                            <div className='border-r-2 px-2'>
                                <div className='mb-2'>Warna</div>
                                <ColorSelectionInput
                                    itemSelected={color?.id}
                                    onItemSelected={(item) => handleSelectColor(item)}
                                    selectedProductionId={production?.id}
                                />
                            </div>
                            <div className='border-r-2 px-2'>
                                <div className='mb-2'>Ukuran</div>
                                <SizeSelectionInput
                                    itemSelected={size?.id}
                                    onItemSelected={(item) => handleSize(item)}
                                    selectedProductionId={production?.id}
                                />
                            </div>
                        </div>
                        {item && (<>
                            <FormInput
                                type="number"
                                name="qty"
                                value={data.qty}
                                onChange={handleOnChange}
                                label="Operator"
                                error={errors.qty}
                            />
                            <div className='flex flex-row gap-2 w-full justify-around h-20'>
                               
                                <div className='w-1/3 flex items-end'>
                                    <button
                                        type="button"
                                        className="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full h-full flex items-center justify-center"
                                        onClick={() => addQuantity()}
                                    >
                                        <HiOutlinePlusCircle className='w-10 h-10' />
                                    </button>
                                </div>
                                <div className='w-1/3'>
                                    <label className="block mb-2 font-medium text-gray-900">Quantity</label>
                                    <Input
                                        type="number"
                                        label="Quantity"
                                        value={data.finish_quantity}
                                        onChange={handleOnChange}
                                        name="finish_quantity"
                                        className={"text-lg"}
                                    />
                                </div>
                                <div className='w-1/3 flex flex-col justify-between'>
                                    <label className="block mb-2 font-medium text-gray-900 dark:text-white">Sisa</label>
                                    <Input
                                        value={item?.left_quantity}
                                        readOnly={true}
                                        className={"text-lg"}
                                    />
                                </div>
                            </div>
                            <div className='flex flex-row gap-2 w-full justify-around h-20 items-center'>
                                <div className='w-1/3 flex items-end h-full'>
                                    <button
                                        type="button"
                                        className="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg px-5 py-2.5 text-center w-full h-full flex items-center justify-center text-lg font-bold"
                                        onClick={() => addReject()}
                                    >
                                        Reject
                                    </button>
                                </div>
                                <div className='w-1/3'>
                                    <Input
                                        type="number"
                                        value={data.reject_quantity}
                                        onChange={handleOnChange}
                                        name="reject_quantity"
                                        className={"text-lg"}
                                    />
                                </div>
                                <div className='w-1/3 flex flex-col justify-between invisible'>
                                    <div>Kosong</div>
                                </div>
                            </div>
                            <div className='flex flex-row gap-2 w-full justify-around h-20 items-center'>
                                <div className='w-1/3 pt-2 flex text-center justify-center'>
                                    <div className='w-24 font-bold text-2xl'>Total</div>
                                </div>
                                <div className='w-1/3'>
                                    <Input
                                        type="number"
                                        value={+data.finish_quantity + +data.reject_quantity}
                                        readOnly={true}
                                        className={"text-lg"}
                                    />
                                </div>
                                <div className='w-1/3 flex flex-col justify-between'>
                                    <Input
                                        type="number"
                                        value={item?.left_quantity - (+data.finish_quantity + +data.reject_quantity)}
                                        readOnly={true}
                                        className={"text-lg"}
                                    />
                                </div>
                            </div>
                            <div className='w-full flex flex-row h-20'>
                                <div className='w-1/3 flex justify-center'>
                                    <button
                                        type="button"
                                        className="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg px-5 py-2.5 text-center h-full w-full text-lg font-bold"
                                        disabled={processing}
                                        onClick={() => handleSubmit()}
                                    >
                                        Simpan
                                    </button>
                                </div>
                            </div>
                            <div className='border-2 rounded-lg p-2 w-full overflow-y-auto'>
                                <label className='text-lg ml-2'>Hasil</label>
                                <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                                    <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" className="py-3 px-6">
                                                User
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Waktu
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Quantity
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Reject
                                            </th>
                                            <th scope="col" className="py-3 px-6">
                                                Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {item?.results?.map(item => (
                                            <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={item.id}>
                                                <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                    {item.creator.name}
                                                </td>
                                                <td className="py-4 px-6">
                                                    {item.input_at}
                                                </td>
                                                <td className="py-4 px-6">
                                                    {item.finish_quantity}
                                                </td>
                                                <td className="py-4 px-6">
                                                    {item.reject_quantity}
                                                </td>
                                                <td className="py-4 px-6">
                                                    {+item.reject_quantity + +item.finish_quantity}
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                        </>)}
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}