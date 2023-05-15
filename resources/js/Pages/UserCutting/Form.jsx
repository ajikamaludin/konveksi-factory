import React, { useEffect, useState } from 'react';
import { router,useForm } from '@inertiajs/react';
import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import ProductionSelectionInput from '../Cutting/SelectionInput';
import RatioSelected from '../Ratio/SelectedInput'
import FabricSelectionInput from '../Fabric/SeletedInputFabric'
import FormInput from '@/Components/FormInput';
import { isEmpty } from 'lodash';
import { HiOutlinePlusCircle, HiXCircle } from 'react-icons/hi';
import Button from '@/Components/Button';
import { usePrevious } from 'react-use';
import { formatDate } from '@/utils';


export default function Form(props) {
    const { userCutting } = props
    const { data, setData, post, processing, errors, reset, clearErrors } = useForm({
        ratio_id: '',
        production_id: '',
        fabric_item_id: '',
        kode_lot: '',
        total_po: 0,
        fritter_po: 0,
        fritter_quantity:0,
        items: [],
    });
    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)
    const [ratio_qty, setRatioQty] = useState(1)
    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }
    const onSeletedProduct = (production) => {
        if (isEmpty(production) === false) {
            // console.log(production)
            var total_po=production.result_quantity+production.fritter_quantity
            setData({ fabric_item_id: data.fabric_item_id, items: data.items, production_id: production?.production_id, ratio_id: data.ratio_id, total_po: total_po, fritter_po: production?.fritter_quantity,fritter_quantity:production.fritter_quantity, kode_lot: data.kode_lot })
            setSearch({ ...search, production_id: production.production_id })
            return
        } else {
            setData({ fabric_item_id: data.fabric_item_id, items: [], production_id: '', ratio_id: data.ratio_id, total_po: 0, kode_lot: data.kode_lot, fritter_po: data.fritter_po,fritter_quantity:data.fritter_quantity })
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
                    fritter_item: detail.fritter,
                };

            });
         
            setData({ fabric_item_id: fabric.first_item.id, items: items, production_id: data.production_id, ratio_id: data.ratio_id, kode_lot: fabric.first_item.code, total_po: data.total_po, fritter_po: data.fritter_po ,fritter_quantity:data.fritter_quantity})
            setSearch({ ...search, fabric_item_id: fabric.first_item.id })

            return
        } else {
            setData({ fabric_item_id: '', items: [], production_id: data.production_id, ratio_id: data.ratio_id, total_po: data.total_po, kode_lot: data.kode_lot, fritter_po: data.fritter_po ,fritter_quantity:data.fritter_quantity})
            setSearch({ ...search, fabric_item_id: '' })
        }
    }

    const onSeletedRatio = (ratio) => {
        if (isEmpty(ratio) === false) {
            const qtyratio = ratio.details_ratio.reduce((sum, val) =>
                sum += val.qty, 0
            )
            setRatioQty(qtyratio)
            setData({ fabric_item_id: data.fabric_item_id, items: data.items, production_id: data.production_id, ratio_id: ratio.id, total_po: data.total_po, kode_lot: data.kode_lot, fritter_po: data.fritter_po ,fritter_quantity:data.fritter_quantity})
            setSearch({ ...search, ratio_id: ratio?.id })
          
            return
        } else {
            setSearch({ ...search, ratio_id: '' })
            setData({ fabric_item_id: data.fabric_item_id, items: [], production_id: data.production_id, ratio_id: '', total_po: data.total_po, kode_lot: data.kode_lot, fritter_po: data.fritter_po,fritter_quantity:data.fritter_quantity })
        }
    }
    const handleChangeItemValue = (name, value, index) => {
        setData("items", data.items.map((item, i) => {
            if (i === index) {
                if (item.qty >= value&&item.fritter>=value) {
                    item[name] = value,
                        item['total_qty'] = value * ratio_qty,
                        item['fritter_item'] = parseFloat(item.fritter).toFixed(2) - value
                }
            }
            return item
        }))
        SubstractPO();
    }
    

    const addQuantity = (name, value, index) => {
        setData("items", data.items.map((item, i) => {
            if (i === index) {
                if (item.qty > value || item.fritter>= value) {
                    item[name] = parseFloat(value) + 1,
                        item['total_qty'] = (parseFloat(value) + 1) * ratio_qty,
                        item['fritter_item'] = parseFloat(item.fritter).toFixed(2) - (parseFloat(value) + 1).toFixed(2)
                }
            }
            return item
        }))
        SubstractPO();
    }
    const SubstractPO = () => {
        const qty = data.items.reduce((qty, item) => qty += item.total_qty, 0);
        let fritter_quantity=parseFloat(data.fritter_po)-parseFloat(qty);
       
        setData('fritter_quantity', fritter_quantity.toFixed(2))
    }
     const handleReset = () => {
        reset()
       
    }
    const handleSubmit = () => {
        post(route('user-cutting.store'), {
            // onSuccess: () => handleReset()
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
            
        }
    }, [search])
   
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
                                                                value={(+item.quantity)}
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
                                                                value={item?.fritter_item.toFixed(2)}
                                                                readOnly={true}
                                                            />
                                                        </td>
                                                    </tr>
                                                ))}
                                                <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" >
                                                    <td colSpan="4" scope="row" className="text-center py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                        <b>Sisa PO</b>
                                                    </td>
                                                    <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                                        {data.fritter_quantity}
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </>
                            )
                        }
                        <div className='mt-10'>
                            <Button
                                onClick={handleSubmit}
                                processing={processing}
                            >
                                Simpan
                            </Button>
                        </div>
                        {userCutting?.length>0 && (<>
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
                                                Sisa
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {
                                            userCutting.map((val) => (
                                                <tr className="bg-white border-b dark:bg-gray-800 dark:border-gray-700" key={val.id}>
                                                    <td scope="row" className="py-4 px-6 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                                        {val?.user_cutting_item[0].creator.name}
                                                    </td>
                                                    <td className="py-4 px-6">
                                                        {formatDate(val.created_at)}
                                                    </td>
                                                    <td className="py-4 px-6">

                                                        {
                                                            val?.user_cutting_item.reduce((sum, detailitem) =>
                                                                sum += detailitem.qty, 0
                                                            )}

                                                    </td>
                                                    <td className="py-4 px-6">
                                                        {
                                                            val?.user_cutting_item[val?.user_cutting_item.length-1]?.fritter
                                                        }
                                                    </td>
                                                </tr>
                                            ))
                                        }
                                    </tbody>
                                </table>
                            </div>
                        </>)}

                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )


}