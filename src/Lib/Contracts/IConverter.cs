namespace DCA.Lib.Contracts;

public interface IConverter<in T> where T : ICurrency
{
    public Bitcoin Convert(T amount);
}
