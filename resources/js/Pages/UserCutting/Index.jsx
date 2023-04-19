import React, { useEffect, useState } from 'react';
import { Link, router, useForm } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProductionSelectionInput from '../Production/SelectionInput';
import RatioSelected from '../Ratio/SelectedInput'
import FabricItemSelected from '../Fabric/SelectedInputFabricItem'
import FormInput from '@/Components/FormInput';
import { useModalState } from '@/hooks';
import FormModal from './FormModal';
import { isEmpty } from 'lodash';
import { HiXCircle } from 'react-icons/hi';
import Button from '@/Components/Button';

export default function Index(props) {
    const { auth } = props
    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({

        ratio_id: '',
        production_id: '',
        fabric_item_id: '',
        items: [],
    })
    const [total_po, setTotal] = useState(0)
    const [ratio_qty, setRatioQty] = useState(1)
    const [detailFabric, setDetailFabric] = useState([])
    const formItemModal = useModalState()

    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }
    const onSeletedProduct = (production) => {
        if (isEmpty(production) === false) {
            setData('production_id', production?.id)
            setTotal(production.total)
        } else {
            setData('production_id', '')
           
        }

    }
    const onSeletedFabric = (fabric) => {
        if (isEmpty(fabric) === false) {
            setData('fabric_item_id', fabric.id)
            setDetailFabric(fabric.detail_fabrics)
        } else {
            setData({fabric_item_id:'',items:[],production_id:data.production_id,ratio_id:data.ratio_id})
        }
    }
    const onSeletedRatio = (ratio) => {
        if (isEmpty(ratio) === false) {
            const qtyratio = ratio.details_ratio.reduce((sum, val) =>
                sum += val.qty, 0
            )
            setRatioQty(qtyratio)
            setData('ratio_id', ratio.id)
        } else {
            setData('ratio_id', '')
           
        }
    }
    const handleReset = () => {
        reset()
    }
    const onItemAdd = (item) => {
        setData("items", data.items.concat(item))
    }
    const onItemRemove = (index) => {
        setData("items", data.items.filter((it, i) => i !== index))
    }
    const handleSubmit = () => {
        post(route('user-cutting.store', item), {
            onSuccess: () => handleReset()
        })
    }
// console.log(data.items)
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'User Cutting'}
        >
            <Head title="User Cutting" />
            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white space-y-6 min-h-screen">
                        <div className='text-xl font-bold mb-4'>User Cutting</div>
                        <div className='grid grid-cols-4 text-center'>
                            <div className='border-x-2 px-2'>
                                <ProductionSelectionInput
                                    label="Artikel"
                                    itemSelected={data.production_id}
                                    onItemSelected={(production) => onSeletedProduct(production)}
                                />
                            </div>
                            <div className='border-r-2 px-2'>
                                <FabricItemSelected
                                    label="Kode Lot"
                                    itemSelected={data.fabric_item_id}
                                    onItemSelected={(fabric) => onSeletedFabric(fabric)}
                                    error={errors.fabric_item_id}
                                />
                            </div>
                            <div className='border-r-2 px-2'>
                                <RatioSelected
                                    label="Ratio"
                                    itemSelected={data.ratio_id}
                                    onItemSelected={(ratio) => onSeletedRatio(ratio)}
                                    error={errors.ratio_id}
                                />
                            </div>
                            <div className='border-r-2 px-2'>
                                <FormInput
                                    name="total_po"
                                    value={total_po}
                                    onChange={handleOnChange}
                                    label="Total PO"
                                    error={errors.composisi}
                                />
                            </div>
                        </div>
                        {
                            data.production_id != '' && data.fabric_item_id != '' && data.ratio_id != '' && (<>
                                <label>Item</label>
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
                                                    Kain
                                                </th>
                                                <th scope="col" className="py-3 px-6">
                                                    Jumlah Lembar
                                                </th>
                                                <th scope="col" className="py-3 px-6">
                                                    Quantity
                                                </th>
                                                <th scope="col" className="py-3 px-6" />
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {data.items.map((item, index) => (
                                                <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={index}>
                                                    <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {item?.detail_fabric?.qty}
                                                    </td>
                                                    <td className="py-4 px-6">
                                                        {item?.quantity}
                                                    </td>
                                                    <td className="py-4 px-6">
                                                        {item?.total_qty}
                                                    </td>
                                                    <td>

                                                        <HiXCircle className="w-5 h-5 text-red-600" onClick={() => onItemRemove(index)} />


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
                            </>
                            )
                        }

                    </div>
                </div>
            </div>
            <FormModal
                modalState={formItemModal}
                onItemAdd={onItemAdd}
                detailFabric={detailFabric}
                ratio_qty={ratio_qty}
            />
        </AuthenticatedLayout>
    );
}