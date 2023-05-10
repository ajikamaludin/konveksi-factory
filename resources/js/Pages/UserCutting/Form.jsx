import React, { useEffect, useState } from 'react';
import { router, useForm } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProductionSelectionInput from '../Production/SelectionInput';
import RatioSelected from '../Ratio/SelectedInput'
import FabricSelectionInput from '../Fabric/SeletedInputFabric'
import FormInput from '@/Components/FormInput';
import { isEmpty } from 'lodash';
import { HiOutlinePlusCircle, HiXCircle } from 'react-icons/hi';
import Button from '@/Components/Button';


export default function Form(props) {
    const { userCutting } = props
    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
        ratio_id: '',
        production_id: '',
        fabric_item_id: '',
        kode_lot: '',
        total_po: 0,
        fritter_po:0,
        items: [],
    });
    const [search, setSearch] = useState('');
    const [ratio_qty, setRatioQty] = useState(1)
    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }
    const onSeletedProduct = (production) => {
        if (isEmpty(production) === false) {
            setData({ fabric_item_id: data.fabric_item_id, items: data.items, production_id: production?.id, ratio_id: data.ratio_id, total_po: production.total,fritter_po:production.left, kode_lot: data.kode_lot })
            setSearch({ ...search, production_id: production.id })
            return
        } else {
            setData({ fabric_item_id: data.fabric_item_id, items: [], production_id: '', ratio_id: data.ratio_id, total_po: 0, kode_lot: data.kode_lot,fritter_po:data.fritter_po })
            setSearch({ ...search, production_id: '' })
        }
    }
    const onSeletedFabric = (fabric) => {
        if (isEmpty(fabric) === false) {
            let items = fabric.first_item.detail_fabrics.map((detail) => {
                return {
                    ...detail,
                    quantity: 0,
                    total_qty: 0,
                    fritter: 0,
                };

            });
            setData({ fabric_item_id: fabric.first_item.id, items: items, production_id: data.production_id, ratio_id: data.ratio_id, kode_lot: fabric.first_item.code, total_po: data.total_po,fritter_po:data.fritter_po })
            setSearch({ ...search, fabric_item_id: fabric.id })

            return
        } else {
            setData({ fabric_item_id: '', items: [], production_id: data.production_id, ratio_id: data.ratio_id, total_po: data.total_po, kode_lot: data.kode_lot,fritter_po:data.fritter_po })
            setSearch({ ...search, fabric_item_id: '' })
        }
    }

    const onSeletedRatio = (ratio) => {
        if (isEmpty(ratio) === false) {
            const qtyratio = ratio.details_ratio.reduce((sum, val) =>
                sum += val.qty, 0
            )
            setRatioQty(qtyratio)
            setData({ fabric_item_id: data.fabric_item_id, items: data.items, production_id: data.production_id, ratio_id: ratio.id, total_po: data.total_po, kode_lot: data.kode_lot,fritter_po:data.fritter_po })
            setSearch({ ...search, ratio_id: ratio?.id })
            return
        } else {
            setSearch({ ...search, ratio_id: '' })
            setData({ fabric_item_id: data.fabric_item_id, items: [], production_id: data.production_id, ratio_id: '', total_po: data.total_po, kode_lot: data.kode_lot,fritter_po:data.fritter_po })
        }
    }
    const handleChangeItemValue = (name, value, index) => {
        setData("items", data.items.map((item, i) => {
            if (i === index) {
                if (item.qty >= value) {
                    item[name] = value,
                        item['total_qty'] = value * ratio_qty,
                        item['fritter'] = parseInt(item.qty) - value

                }
            }
            return item
        }))
        SubstractPO();
    }

    const addQuantity = (name, value, index) => {
        setData("items", data.items.map((item, i) => {
            if (i === index) {
                if (item.qty > value) {
                    item[name] = parseInt(value) + 1,
                        item['total_qty'] = (parseInt(value) + 1) * ratio_qty,
                        item['fritter'] = parseInt(item.qty) - (parseInt(value) + 1)
                }
            }
            return item
        }))
        SubstractPO();
    }
    const SubstractPO=()=>{
        const qty=data.items.reduce((qty,item)=>qty += item.total_qty, 0)
        setData('fritter_po',parseInt(data.total_po-qty))
    }

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
                        <div className='grid grid-cols-5 text-center'>
                            <div className='border-x-2 px-2'>
                                <ProductionSelectionInput
                                    label="Artikel"
                                    itemSelected={data.production_id}
                                    onItemSelected={(production) => onSeletedProduct(production)}
                                />
                            </div>
                            <div className='border-r-2 px-2'>
                                <FabricSelectionInput
                                    label="Kain"
                                    itemSelected={data.fabric_item_id}
                                    onItemSelected={(fabric) => onSeletedFabric(fabric)}
                                    error={errors.fabric_item_id}
                                />
                            </div>
                            <FormInput
                                name="kode_lot"
                                value={data.kode_lot}
                                onChange={handleOnChange}
                                label="Kode Lot"
                                readOnly={true}
                                error={errors.kode_lot}
                            />
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
                                    value={data.total_po}
                                    onChange={handleOnChange}
                                    label="Total PO"
                                    error={errors.composisi}
                                />
                            </div>
                        </div>
                        {
                            data.items != '' && data.fabric_item_id != '' && data.ratio_id != '' && (
                                <>
                                    <label>Item</label>
                                    <div className='w-full flex flex-col border-2 rounded-lg p-2'>
                                        <table className="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-4">
                                            <thead className="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                                <tr>
                                                    <th scope="col" className="py-3 px-6">
                                                        #
                                                    </th>
                                                    <th scope="col" className="py-3 px-6">
                                                        Kain
                                                    </th>
                                                    <th scope="col" className="py-3 px-6">
                                                        Jumlah Lembar
                                                    </th>
                                                    <th scope="col" className="py-3 px-6">
                                                        Quantity
                                                    </th>
                                                    <th scope="col" className="py-3 px-6">
                                                        Sisa Kain
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {data.items.map((item, index) => (
                                                    <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={index}>
                                                        <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                            <button
                                                                type="button"
                                                                className="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center w-full h-full flex items-center justify-center"
                                                                onClick={() => addQuantity("quantity", item.quantity, index)}
                                                            >
                                                                <HiOutlinePlusCircle className='w-7 h-7' />
                                                            </button>
                                                        </td>
                                                        <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                            {item?.qty}
                                                        </td>
                                                        <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">

                                                            <input
                                                                className={'`mb-2 bg-gray-50 border text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700  dark:placeholder-gray-400 dark:text-white'}
                                                                type="number"
                                                                min="0"
                                                                value={(+item.quantity).toFixed(0)}
                                                                onChange={e => handleChangeItemValue("quantity", e.target.value, index)}
                                                            />
                                                        </td>
                                                        <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                            <FormInput
                                                                value={item?.total_qty}
                                                                readOnly={true}
                                                            />
                                                        </td>
                                                        <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                            <FormInput
                                                                value={item?.fritter}
                                                                readOnly={true}
                                                            />
                                                        </td>
                                                    </tr>
                                                ))}
                                                <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" >
                                                    <td colSpan="4" scope="row" className="text-center py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                       <b>Sisa PO</b> 
                                                    </td>
                                                    <td  scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                        {data.fritter_po}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </>
                            )
                        }

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )


}