import React, { useState, useEffect } from 'react';
import { usePrevious } from 'react-use';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import ProductionSelectionInput from '../Production/SelectionInput';
import FormInput from '@/Components/FormInput';
export default function Index(props) {
    const { _production } = props
    const [search, setSearch] = useState('')
    const preValue = usePrevious(search)
    const [production, setProduction] = useState(_production)
    const handleSelectProduction = (production) => {
        if (isEmpty(production) === false) {
            setProduction(production)
            setSearch({ ...search, production_id: production.id })
            return
        }
        setSearch({ ...search, production_id: '' })
        setProduction('')
    }
    console.log(_production)
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Tv'}
        >
            <Head title="Tv" />
            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white space-y-6 min-h-screen">
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                                <div className='mb-2'>Artikel</div>
                                <ProductionSelectionInput
                                    itemSelected={production?.id}
                                    onItemSelected={(item) => handleSelectProduction(item)}
                                />
                            </div>
                            <div className='border-r-2'>
                                <div className='mb-2'>Line</div>
                                <ProductionSelectionInput
                                    itemSelected={production?.id}
                                    onItemSelected={(item) => handleSelectProduction(item)}
                                />
                            </div>
                            <div className='border-r-2 mt-1'>
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="Jam"
                                />
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                               
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="Target"
                                />
                            </div>
                            <div className='border-r-2'>
                                
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="Hasil"
                                />
                            </div>
                            <div className='border-r-2'>
                               
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="Sisa PO"
                                />
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                               
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="Operator"
                                />
                            </div>
                            <div className='border-r-2'>
                               
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="Perkiraan"
                                />
                            </div>
                            <div className='border-r-2'>
                              
                                <FormInput
                                    type="number"
                                    name="qty"
                                    value={0}
                                    readOnly={true}
                                    label="HPP"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    )
}