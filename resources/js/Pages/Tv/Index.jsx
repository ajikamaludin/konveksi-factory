import React, { useState, useEffect } from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, router } from '@inertiajs/react';
import { formatIDR } from '@/utils';
import Button from '@/Components/Button';

export default function Index(props) {
    const { _production, hourline, target, operator, hpp, hasil } = props
    
    const [isfull, setFull] = useState(false)
    const handlefull = () => {
         var element = document.querySelector("#container");
        if (isfull==false){
            element.requestFullscreen().then(function(){setFull(true)}).catch(function(error){});
        }else{
            document.exitFullscreen().then(function(){setFull(false)}).catch(function(error){});
        }
       
            

    }
    useEffect(() => {
        setTimeout(() =>
            router.get(
                route(route().current())
            )
            , 30000);
    }, [_production])

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Tv'}
        >
            <Head title="Tv" />

            <div id="container">
                <div className={ " mx-auto sm:px-6 lg:px-8"} >
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white space-y-6 min-h-screen">
                        <div className='grid grid-cols-4 text-center'>
                            <div className='border-r-2'>
                                <div className='mb-2 text-4xl'>{_production?.name}</div>
                            </div>
                            <div className='border-r-2'>
                                <div className='mb-2 text-4xl'>{_production?.active_line}</div>
                            </div>
                            <div className='border-r-2 mt-1'>
                                <div div className='mb-2 text-4xl'>PO {formatIDR(_production?.total)}</div>
                            </div>
                            <div className='border-r-2 mt-1'>
                                <div div className='mb-2 text-4xl'>Jam {hourline}</div>
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                                <div div className='mb-2 text-4xl'>Target</div>
                                <div div className='mb-2 text-4xl'>{target}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2 text-4xl'>Hasil</div>
                                <div div className='mb-2 text-4xl'>{hasil}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2 text-4xl'>Sisa PO</div>
                                <div div className='mb-2 text-4xl'>{formatIDR(_production?.left)}</div>
                            </div>
                        </div>
                        <div className='grid grid-cols-3 text-center'>
                            <div className='border-r-2'>
                                <div div className='mb-2 text-4xl'>Operator</div>
                                <div div className='mb-2 text-4xl'>{operator}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2 text-4xl'>Perkiraan</div>
                                <div div className='mb-2 text-4xl text-4xl'>{target}</div>
                            </div>
                            <div className='border-r-2'>
                                <div div className='mb-2 text-4xl'>HPP</div>
                                <div div className='mb-2 text-4xl text-4xl'>{formatIDR(hpp)}</div>
                            </div>
                        </div>
                        <div className="flex items-center">
                            <Button onClick={handlefull}>{isfull ? 'Mini Screen' : 'Full Screen'}</Button>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )
}