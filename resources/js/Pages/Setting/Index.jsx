import React from 'react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, useForm } from '@inertiajs/react';
import FormInput from '@/Components/FormInput';
import Button from '@/Components/Button';
export default function Index(props) {
    const { settingPayroll } = props
    const { data, setData, put, processing, errors} = useForm({
        payroll: settingPayroll.payroll,
        workhours_sunday: settingPayroll.workhours_sunday,
        workhours_monday: settingPayroll.workhours_monday,
        workhours_tuesday: settingPayroll.workhours_thusday,
        workhours_wednesday: settingPayroll.workhours_wednesday,
        workhours_thusday: settingPayroll.workhours_thusday,
        workhours_friday: settingPayroll.workhours_friday,
        workhours_saturday: settingPayroll.workhours_saturday,

    });
    const handleOnChange = (event) => {
        setData(event.target.name, event.target.type === 'checkbox' ? (event.target.checked ? 1 : 0) : event.target.value);
    }
    const handleSubmit = () => {
        put(route('setting.create', settingPayroll))
    }

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            flash={props.flash}
            page={'Dashboard'}
            action={'Setting'}
        >
            <Head title="Setting" />
            <div>
                <div className="mx-auto sm:px-6 lg:px-8 ">
                    <div className="p-6 overflow-hidden shadow-sm sm:rounded-lg bg-white space-y-6 min-h-screen">
                        <FormInput
                            type="number"
                            name="payroll"
                            value={data.payroll}
                            onChange={handleOnChange}
                            label="Gaji"
                            error={errors.payroll}
                        />
                        <FormInput
                            type="number"
                            name="workhours_sunday"
                            value={data.workhours_sunday}
                            onChange={handleOnChange}
                            label="Minggu"
                            error={errors.workhours_sunday}
                        />
                        <FormInput
                            type="number"
                            name="workhours_monday"
                            value={data.workhours_monday}
                            onChange={handleOnChange}
                            label="Senin"
                            error={errors.workhours_monday}
                        />
                        <FormInput
                            type="number"
                            name="workhours_tuesday"
                            value={data.workhours_tuesday}
                            onChange={handleOnChange}
                            label="Selasa"
                            error={errors.workhours_tuesday}
                        />
                        <FormInput
                            type="number"
                            name="workhours_wednesday"
                            value={data.workhours_wednesday}
                            onChange={handleOnChange}
                            label="Rabu"
                            error={errors.workhours_wednesday}
                        />
                        <FormInput
                            type="number"
                            name="workhours_thusday"
                            value={data.workhours_thusday}
                            onChange={handleOnChange}
                            label="Kamis"
                            error={errors.workhours_thusday}
                        />
                        <FormInput
                            type="number"
                            name="workhours_friday"
                            value={data.workhours_friday}
                            onChange={handleOnChange}
                            label="Jumat"
                            error={errors.workhours_friday}
                        />
                        <FormInput
                            type="number"
                            name="workhours_saturday"
                            value={data.workhours_saturday}
                            onChange={handleOnChange}
                            label="Sabtu"
                            error={errors.workhours_saturday}
                        />
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
        </AuthenticatedLayout>
    )
}