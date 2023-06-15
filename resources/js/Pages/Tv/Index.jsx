import React, { useState, useEffect } from 'react'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout'
import { Head, router } from '@inertiajs/react'
import { formatIDR } from '@/utils'
import Button from '@/Components/Button'

export default function Index(props) {
    const {
        _production,
        hourline,
        target,
        operator,
        hpp,
        hasil,
        creator,
        estimate,
    } = props

    const [isfull, setFull] = useState(false)
    const handlefull = () => {
        var element = document.querySelector('#container')
        if (isfull == false) {
            element
                .requestFullscreen()
                .then(function () {
                    setFull(true)
                })
                .catch(function (error) {})
        } else {
            document
                .exitFullscreen()
                .then(function () {
                    setFull(false)
                })
                .catch(function (error) {})
        }
    }
    useEffect(() => {
        const to = setTimeout(
            () =>
                router.get(
                    route(route().current()),
                    {},
                    {
                        preserveState: true,
                    }
                ),
            1000 * 60 * 5 //5 menit
        )

        return () => clearTimeout(to)
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
                <div className="mx-auto sm:px-6 lg:px-8">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white h-screen flex flex-col justify-center space-y-16">
                        <div className="grid grid-cols-4 text-center items-center mt-4">
                            <div className="border-r">
                                <div
                                    className={`mb-2 font-bold ${
                                        isfull ? 'text-7xl' : 'text-5xl'
                                    }`}
                                >
                                    {_production == null
                                        ? '-'
                                        : _production?.name}
                                </div>
                            </div>
                            <div className="border-r">
                                <div
                                    className={`mb-2 font-bold ${
                                        isfull ? 'text-7xl' : 'text-5xl'
                                    }`}
                                >
                                    {creator}
                                </div>
                            </div>
                            <div className="border-r">
                                <div
                                    div
                                    className={`mb-2 font-bold ${
                                        isfull ? 'text-7xl' : 'text-5xl'
                                    }`}
                                >
                                    PO{' '}
                                    {_production == null
                                        ? '-'
                                        : formatIDR(_production?.total)}
                                </div>
                            </div>
                            <div>
                                <div
                                    div
                                    className={`mb-2 font-bold ${
                                        isfull ? 'text-7xl' : 'text-5xl'
                                    }`}
                                >
                                    Jam {hourline}
                                </div>
                            </div>
                        </div>
                        <div className="grid grid-cols-3 text-center items-center">
                            <div className="border-r-2 p-1">
                                <div div className="mb-6 text-7xl">
                                    Target
                                </div>
                                <div div className="mb-6 text-7xl font-bold">
                                    {target}
                                </div>
                            </div>
                            <div className="border-r-2 p-1">
                                <div div className="mb-6 text-7xl">
                                    Hasil
                                </div>
                                <div div className="mb-6 text-7xl font-bold">
                                    {hasil}
                                </div>
                            </div>
                            <div className="p-1">
                                <div div className="mb-6 text-7xl ">
                                    Sisa PO
                                </div>
                                <div div className="mb-6 text-7xl font-bold">
                                    {' '}
                                    {_production == null
                                        ? '-'
                                        : formatIDR(_production?.left)}{' '}
                                </div>
                            </div>
                        </div>
                        <div className="grid grid-cols-3 text-center items-center">
                            <div className="border-r-2 p-1">
                                <div div className="mb-6 text-7xl">
                                    Operator
                                </div>
                                <div div className="mb-6 text-7xl font-bold">
                                    {operator}
                                </div>
                            </div>
                            <div className="border-r-2 p-1">
                                <div div className="mb-6 text-7xl ">
                                    Perkiraan
                                </div>
                                <div div className="mb-6 text-7xl font-bold">
                                    {estimate}
                                </div>
                            </div>
                            <div className="p-1">
                                <div div className="mb-6 text-7xl">
                                    HPP
                                </div>
                                <div div className="mb-6 text-7xl font-bold">
                                    {formatIDR(hpp)}
                                </div>
                            </div>
                        </div>
                        <div className="flex items-center">
                            <Button onClick={handlefull}>
                                {isfull ? 'Mini Screen' : 'Full Screen'}
                            </Button>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    )
}
